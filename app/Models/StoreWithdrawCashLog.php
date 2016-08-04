<?php
/**
 * bannerModel
 * @author  wuhui
 * @time    2016/06-08
 * @email   wuhui904107775@qq.com
 */
namespace App\Models;

use DB, Config;
use Illuminate\Database\Eloquent\Model;
use App\Models\StoreInfo;
use App\Models\StoreBankCard;
use App\Libs\Message;
use Carbon\Carbon;

class StoreWithdrawCashLog extends Model
{

    protected $table = 'store_withdraw_cash_log';
    protected $OK = 1;      //数据库操作错误返回代码
    protected $ERROR = 0; //数据库操作成功返回代码

    /**
     * @param $account
     * @param $password
     * @return mixed
     * 增加提现记录
     */

    public function withdrawCash($data)
    {
        if (!isset($data['store_id'])) {
            return false;
        }

        //获取银行信息
        $storeBankCardModel = new StoreBankCard;
        $bankInfo = $storeBankCardModel->getBankCard($data['store_id'], 0);

        if (!$bankInfo) {
            return Message::setResponseInfo('NO_BANK');
        }
        $data['bank_card_num'] = $bankInfo->bank_card_num;
        $data['bank_card_holder'] = $bankInfo->bank_card_holder;
        $data['bank_card_type'] = $bankInfo->bank_card_type;
        $data['bank_name'] = $bankInfo->bank_name;
        $data['bank_reserved_telephone'] = $bankInfo->bank_reserved_telephone;


        if ($this->getWithdrawCashTimes($data['store_id'], date('Y-m-d', time())) + 1 > Config::get('withdrawcash.times')) {
            return Message::setResponseInfo('NO_TIMES');
        }

        if ($data['withdraw_cash_num'] > Config::get('withdrawcash.total')) {
            return Message::setResponseInfo('EXCEED_MONEY_LIMIT');
        }

        $storeConfigModel = new StoreConfig;
        $storeMoney = DB::table($storeConfigModel->getTable())->select('money')->where('store_id', $data['store_id'])->first();
        $isAmple = $storeMoney->money - $data['withdraw_cash_num'];

        if ($isAmple < 0) {
            return Message::setResponseInfo('MONEY_NOT_AMPLE');
        } else {
            $data['can_withdraw_cash_num'] = $storeMoney->money;
        }

        DB::beginTransaction();

        try {

            $storeConfigModel->config($data['store_id'], array('money' => $isAmple));

            DB::table($this->table)->insert($data);
            DB::commit();
            return Message::setResponseInfo('SUCCESS');

        } catch (Exception $e) {
            DB::rollBack();

            return Message::setResponseInfo('FAILED');
        }

        return Message::setResponseInfo('FAILED');
    }

    /**
     * @param $account
     * @param $password
     * @return mixed
     * 获取提现记录
     */
    public function getWithdrawCashLogByStoreId($storeId, $length, $offset, $date = '')
    {
        /*
        *				store_withdraw_cash_log.created_at, //申请提现时间
                        store_infos.name, 					//商户名称
                        store_withdraw_cash_log.store_id,    //商户ID
                        store_withdraw_cash_log.updated_at,    //当期结算日期
                        store_withdraw_cash_log.withdraw_cash_num,//申请提现金额
                        store_configs.money,                	  //可提现金
                        store_configs.balance,//上期余额
        */

        $sql = "
			SELECT
				store_withdraw_cash_log.id,
				store_withdraw_cash_log.created_at,
	            store_infos.name,
	            store_withdraw_cash_log.store_id,
	            store_withdraw_cash_log.updated_at,
	            store_withdraw_cash_log.withdraw_cash_num,
	            store_configs.money,
	            store_configs.balance
	        FROM
	            store_withdraw_cash_log,
	            store_infos,
	            store_configs
	        WHERE
	            store_withdraw_cash_log.store_id = store_infos.id and store_configs.store_id=store_infos.id";


        $result1 = DB::select($sql);


        for ($i = 0; $i < count($result1); $i++) {

            //组合数据
            $result[$i]['created_at'] = $result1[$i]->created_at;
            $result[$i]['name'] = $result1[$i]->name;
            $result[$i]['store_id'] = $result1[$i]->store_id;
            $result[$i]['updated_at'] = $result1[$i]->updated_at;
            $result[$i]['withdraw_cash_num'] = $result1[$i]->withdraw_cash_num;//提现金额
            $result[$i]['money'] = $result1[$i]->money;                        //可提现金额
            $result[$i]['balance'] = $result1[$i]->balance;                    //上期余额
            $result[$i]['now_balance'] = $result1[$i]->balance - $result1[$i]->withdraw_cash_num; //当期余额
            $result[$i]['id'] = $result1[$i]->id;
            $ID = $result1[$i]->store_id; //获取商户ID

            //获取上期日期
            $getLastTimeSql = "SELECT
                                    store_withdraw_cash_log.updated_at
                                FROM
                                    store_withdraw_cash_log
                                WHERE
                                    store_withdraw_cash_log.store_id = $ID and status =0";

            $tempResult1 = DB::select($getLastTimeSql);
            if (isset($tempResult1[1])) {
                $lastTime = strtotime($tempResult1[1]->store_withdraw_cash_log . updata);//上次提现时间
                $result[$i]['lastTime'] = $tempResult1[1]->store_withdraw_cash_log . updata;
            } else {
                $lastTime = 0;
                $result[$i]['lastTime'] = 0;
            }
            $now = strtotime($result1[$i]->updated_at);                             //这次提现时间
            //得出当期流水 当期收入 当期扣点
            $getTotalsql = "SELECT
                    total,
                    deliver
                FROM
                    orders
                WHERE
                    status =1 and
                    pay_time < $now and
                    pay_time >$lastTime
                ";
            $tempResult2 = DB::select($getTotalsql);

            $all = count($tempResult2);//获取订单总数
            $alltotal = 0;  //当期总费用
            $alldeliver = 0;//当期总运费


            for ($k = 0; $k < count($tempResult2); $k++) {
                $alltotal += $tempResult2[$k] . total;
                $alldeliver += $tempResult2[$k] . deliver;
            }

            $allmoney = $alltotal + $alldeliver;//当期流水

            $income = $allmoney * 0.965; //当期收入

            $remain = $allmoney * 0.035; //当期扣点

            //组合数据
            $result[$i]['allmoney'] = $allmoney;
            $result[$i]['income'] = $income;
            $result[$i]['remain'] = $remain;
            $result[$i]['all'] = $all;
        }
        return $result;
    }


    /**
     * @param $account
     * @param $password
     * @return mixed
     * 获取提现总计
     */
    public function getWithdrawCashTotal($search = array())
    {

        $sql = "SELECT 
                    sum(sw.withdraw_cash_num) as withdraw_cash_total_num
               FROM $this->table as sw";

        $sql .= " WHERE 1=1";

        if (isset($search['store_id'])) {
            $sql .= " AND sw.store_id = " . $search['store_id'];
        }
        if (isset($search['date'])) {
            $sql .= " AND sw.created_at LIKE '" . isset($search['date']) . "%'";
        };

        $result = DB::select($sql);


        return $result;
    }


    public function withdrawCashLogTotalNum($search = array())
    {
        $sql = DB::table($this->table);

        if (isset($search['store_id'])) {
            $sql->where('store_id', $search['store_id']);
        }
        if (isset($search['stop_out'])) {
            $sql->where('stop_out', $search['stop_out']);
        }
        if (isset($search['searchTime'])) {

            $sql->where("updated_at","<",$search['searchTime']);
        }

        return $sql->count();
    }

    /**
     * @param $account
     * @param $password
     * @return mixed
     * 获取提现记录
     */
    public function getWithdrawCashLog($search = array(), $length = 0, $offset = 0)
    {

        $sql = "SELECT 
                    sw.id,
                    su.real_name,
                    sw.withdraw_cash_num,
                    sw.created_at,
                    sw.status,
                    sw.reason,
                    sw.bank_card_num,
                    sw.bank_card_holder,
                    sw.bank_card_type,
                    sw.bank_name,
                    sw.bank_reserved_telephone,
                    sw.can_withdraw_cash_num as money,
                    si.name,
                    si.address,
                    si.province,  
                    si.city,
                    si.county,
                    si.contacts,
                    si.contact_phone,
                    sc.store_id,
                    sc.store_logo,
                    sc.balance
               
               FROM $this->table as sw";

        $sql .= " LEFT JOIN store_users as su on su.id = sw.user_id";
        $sql .= " LEFT JOIN store_infos as si on si.id = sw.store_id";
        $sql .= " LEFT JOIN store_configs as sc on sc.store_id = sw.store_id";

        $sql .= " WHERE 1=1";

        if (isset($search['status'])) {
            $sql .= " AND sw.status = " . $search['status'];
        }

        if (isset($search['store_id'])) {
            $sql .= " AND sw.store_id = " . $search['store_id'];
        }

        if (isset($search['id'])) {
            $sql .= " AND sw.id = " . $search['id'];
        }

        if (isset($search['date'])) {
            $sql .= " AND sw.created_at LIKE '" . $search['date'] . "%'";
        };
        if (isset($search['searchTime'])) {

            $sql.=" AND sw.updated_at <'".$search['searchTime']."'";
        }

        $sql .= " ORDER BY created_at ASC";

        if ($length != 0) {
            $sql .= " LIMIT $offset , $length ";
        }

        $result = DB::select($sql);
        foreach ($result as $r) {
            $r->all_bank_card_num = $r->bank_card_num;
            $r->bank_card_num = preg_replace('/(^.*)\d{4}(\d{4})$/', '\\2', $r->bank_card_num);
            //$r->bank_card_num = substr_replace($r->bank_card_num, '', -1 , 4);
        }

        return $result;
    }


    /**
     * @param $account
     * @param $password
     * @return mixed
     * 获取今日提现的次数
     */
    public function getWithdrawCashTimes($storeId, $date = '')
    {

        $sql = "SELECT 
                    count(*) as num
               
               FROM $this->table as sw";

        $sql .= " WHERE sw.store_id = $storeId";
        if ($date) {
            $sql .= " AND sw.created_at LIKE '" . $date . "%'";
        };

        $result = DB::select($sql);

        return isset($result[0]) ? $result[0]->num : 0;
    }


    /**
     * @param $storeId
     * @param string $date
     * @return array
     * 提现配置
     */
    public function withdrawCashConfig($storeId, $date = '')
    {
        $data = array();
        $data['used_times'] = $this->getWithdrawCashTimes($storeId, $date);
        $data['times'] = Config::get('withdrawcash.times');
        $data['total'] = Config::get('withdrawcash.total');
        $data['using_times'] = $data['times'] - $data['used_times'];
        $storeConfig = DB::table('store_configs')->where('store_id', $storeId)->first();

        $data['can_withdraw_cash'] = $storeConfig->money;

        return $data;
    }

    /**
     * @param $id 记录id
     * @param $data 需要修改的数组表单
     * @return mixed
     *  修改记录状态
     */

    public function updateWithdraw($id, $data)
    {
        return DB::table($this->table)
            ->where('id', $id)
            ->update($data);
    }

}