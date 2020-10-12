# 1、发布配置文件
    php artisan vendor:publish --provider="Smile\FileManage\AppServiceProvider"
# 2、迁移数据库文件
    php artisan migrate
# 3、使用方法
    1,直接通过内部的已注册的域名使用
    域名/api/upload
   
    2.通过门面直接使用
    use FileManage;

    class IndexController extends Controller
    {
        public function login(Request $request)
        {
            FileManage::upload($request);
        }
    }
 