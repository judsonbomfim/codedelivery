<?php

namespace CodeDelivery\Repositories;

use Illuminate\Database\Eloquent\Collection;
use PhpParser\Node\Stmt\If_;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use CodeDelivery\Repositories\OrderRepository;
use CodeDelivery\Models\Order;
//use CodeDelivery\Validators\OrderValidator;

/**
 * Class OrderRepositoryEloquent
 * @package namespace CodeDelivery\Repositories;
 */
class OrderRepositoryEloquent extends BaseRepository implements OrderRepository
{

    /**
     * @return Application
     */
    public function getByIdAndDeliveryman($id, $idDeliveryman)
    {
        $result = $this->with(['client','items','cupom'])->findWhere([
            'id' => $id,
            'user_deliveryman_id' => $idDeliveryman
        ]);
<<<<<<< HEAD
        $result = $result->first();
        if($result){
=======
        if($result instanceof Collection){
            $result = $result->first();
>>>>>>> origin/master
            $result->items->each(function($item){
                $item->product;
            });
        }
        return $result;
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Order::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}