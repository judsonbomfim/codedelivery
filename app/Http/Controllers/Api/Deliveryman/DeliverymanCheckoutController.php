<?php

namespace CodeDelivery\Http\Controllers\Api\Deliveryman;

use CodeDelivery\Http\Controllers\Controller;
use CodeDelivery\Repositories\OrderRepository;
use CodeDelivery\Repositories\UserRepository;
use CodeDelivery\Services\OrderServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;

class DeliverymanCheckoutController extends Controller
{
    /**
     * @var OrderRepository
     */
    private $repository;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var OrderServices
     */
    private $services;

    public function __construct(
        OrderRepository $repository,
        UserRepository $userRepository,
        OrderServices $services
    )
    {
        $this->repository = $repository;
        $this->userRepository = $userRepository;
        $this->services = $services;
    }

    public function index()
    {
        $id = Authorizer::getResourceOwnerId();
        $orders = $this->repository->with('items')->scopeQuery(function ($query) use($id) {
            return $query->where('user_deliveryman_id','=',$id);
        })->paginate();
        return $orders;
    }

    public function show($id)
    {
        $idDeliveryman = Authorizer::getResourceOwnerId();
        return $this->repository->getByIdAndDeliveryman($id, $idDeliveryman);
    }

    public function updateStatus(Request $request, $id){
        $idDeliveryman = Authorizer::getResourceOwnerId();
        $order = $this->services->updateStatus($id, $idDeliveryman, $request->get('status'));
        if($order){
            return $order;
        }
        abort(400, 'Order não encontrada!');
    }
}