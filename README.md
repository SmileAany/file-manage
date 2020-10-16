### 介绍
安装包基于Laravel8开发，目前只支持Laravel框架中单一附件上传，删除，覆盖，重命名等功能
### 安装配置
- 发布配置
    
``` php artisan vendor:publish --provider="Smbear\FileManage\AppServiceProvider" ```

- 迁移数据

​``` php artisan migrate ```
### 使用教程
- 门面使用
    - 附件上传
        ```
            use FileManage
            
            FileManage::upload($request); 
        ```
    - 附件删除
        ```
            use FileManage
            
            //附件的id
            FileManage::destroy($id); 
        ```
    - 附件重命名
        ```
            use FileManage
            
            //附件的新名称
            FileManage::rename($file_name); 
        ```
    - 附件覆盖
        ```
            use FileManage
            
            //附件id
            FileManage::cover($id,$request); 
        ```
    - 附件列表
        ```
            use FileManage
            
            //附件id
            FileManage::index($request); 
        ```
- 路由使用
    - 附件上传
        ### url(post) : ` http://xx.com/api/file/upload `
    - 附件删除
        ### url(delete) : ` http://xx.com/api/file/destroy `
    - 附件重命名
        ### url(put) : ` http://xx.com/api/file/rename `
    - 附件覆盖
        ### url(post) : ` http://xx.com/api/file/cover `
### 注意事项
- 新建一个Files模型,继承Smbear\FileManage\Models\Files，实现user关联
    ```
    <?php
    
    namespace App\Models;
    
    use Smbear\FileManage\Models\Files AS BaseFiles;
    
    class Files extends BaseFiles
    {
        public function user()
        {
            return $this->belongsTo(Mation::class,'user_id','user_id');
        }
    }

    
    ```
- 所有的路由均可自由配置路由前缀和中间件（file.php配置文件）
    
