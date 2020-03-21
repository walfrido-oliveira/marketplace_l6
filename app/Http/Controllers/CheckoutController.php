<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Payment\PagSeguro\CreditCard;

class CheckoutController extends Controller
{
    /**
     * Show checkout view
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!auth()->check()) return redirect()->route('login');

        if(!session()->has('cart')) return redirect()->route('home');

        $this->makePagSeguroSession();

        $total = 0;

        $cartItems = array_map(function($line) {
            return $line['amount'] * $line['price'];
        }, session()->get('cart'));

        $cartItems = array_sum($cartItems);

        return view('checkout', compact('cartItems'));
    }

    /**
     * Proccess payment
     *
     *
     * @param \Illuminate\Http\Request $request
     */
    public function proccess(Request $request)
    {
        try {

            $dataPost = $request->all();
            $cartItems = session()->get('cart');
            $stores = array_unique(array_column($cartItems, 'store_id'));
            $user = auth()->user();
            $reference = 'XPTO';

            $creditCardPayment = new CreditCard($cartItems, $user, $dataPost, $reference);
            $result = $creditCardPayment->doPayment();

            $userOrder = [
                'reference' => $reference,
                'pagseguro_code' => $result->getCode(),
                'pagseguro_status' => $result->getStatus(),
                'items' => serialize($cartItems),
                'store_id' => 42
            ];

            $userOder = $user->orders()->create($userOrder);
            $userOder->stores()->sync($stores);

            session()->forget('cart');
            session()->forget('pagseguro_sesion_code');

            return response()->json([
                'data' => [
                    'status' => true,
                    'message' => 'Pedido criado com sucesso!',
                    'order' => $reference
                ]
            ]);

        } catch (\Exception $e) {
            $message = env('APP_DEBUG') ? $e->getMessage() : 'Erro ao processar pedido!';
            return response()->json([
                'data' => [
                    'status' => false,
                    'message' => $message
                ]
            ], 401);
        }

    }

    /**
     * Display thanks view
     *
     * @return \Illuminate\Http\Response
     */
    public function thanks()
    {
        return view('thanks');
    }

    /**
     * Make a token session from pagseguro
     *
     */
    private function makePagSeguroSession()
    {
        if(!session()->has('pagseguro_sesion_code')) {
            $sessionCode = \PagSeguro\Services\Session::create(
                \PagSeguro\Configuration\Configure::getAccountCredentials()
            );
            session()->put('pagseguro_sesion_code', $sessionCode->getResult());
        }
    }
}
