<?php

namespace App\Http\Controllers;

use App\Http\Requests\Checkout\StoreCheckoutRequest;
use App\Models\Order;
use App\Models\ShippingZone;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\ShippingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected ShippingService $shippingService,
        protected OrderService $orderService,
    ) {}

    /**
     * Display checkout page
     */
    public function index()
    {
        $cartData = $this->cartService->getCartWithProducts();

        if ($cartData['count'] === 0) {
            return redirect()->route('cart.index')
                ->with('error', 'سلة التسوق فارغة');
        }

        $cartItems = $cartData['items'];
        $subtotal = $cartData['total'];

        $shippingZones = ShippingZone::active()->ordered()->get();

        // Initial shipping calc from the user's saved governorate (the JS updates
        // it on selection). Uses the shared resolver so it matches checkout.
        $shippingData = $this->shippingService->resolve(auth()->user()?->governorate, $subtotal);
        $freeShippingThreshold = $shippingData['free_threshold'];
        $shipping = $shippingData['shipping'];
        $total = $subtotal + $shipping;

        return view('checkout.index', compact('cartItems', 'subtotal', 'shippingZones', 'freeShippingThreshold', 'shipping', 'total'));
    }

    /**
     * Process checkout
     */
    public function store(StoreCheckoutRequest $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'سلة التسوق فارغة');
        }

        try {
            $data = $request->validated();
            $data['save_info'] = $request->boolean('save_info');

            $order = $this->orderService->place($data, $cart, Auth::user());
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء معالجة الطلب. يرجى المحاولة مرة أخرى.');
        }

        // SEC-01: redirect to a one-time signed URL so guests (no user_id) can
        // view their own confirmation without the page being publicly enumerable.
        $successUrl = URL::temporarySignedRoute(
            'checkout.success',
            now()->addHours(24),
            ['order' => $order->order_number]
        );

        return redirect($successUrl)
            ->with('success', 'تم إنشاء طلبك بنجاح!')
            ->with('celebrate', true);
    }

    /**
     * Display order success page
     */
    public function success(Request $request, Order $order)
    {
        // Only the order's owner, or someone holding a valid signed link
        // (the link issued at checkout), may view the confirmation page.
        $isOwner = auth()->check() && $order->user_id === auth()->id();

        if (! $request->hasValidSignature() && ! $isOwner) {
            abort(403);
        }

        return view('checkout.success', compact('order'));
    }

    /**
     * Calculate shipping fee via AJAX
     */
    public function calculateShipping(Request $request)
    {
        $request->validate([
            'governorate' => 'required|string',
            'subtotal' => 'nullable|numeric',
        ]);

        // Required subtotal from cart session if not passed
        $subtotal = $request->subtotal ?: $this->cartService->getCartTotal();

        $shippingData = $this->shippingService->resolve($request->governorate, $subtotal);
        $fee = $shippingData['fee'];
        $shipping = $shippingData['shipping'];
        $total = $subtotal + $shipping;

        return response()->json([
            'success' => true,
            'fee' => $fee,
            'shipping' => $shipping,
            'total' => $total,
            'is_free' => $shipping == 0,
            'message' => $shipping == 0 ? 'شحن مجاني' : number_format($shipping) . ' ج.م',
        ]);
    }
}
