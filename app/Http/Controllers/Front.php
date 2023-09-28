<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Home;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cookie;
use Config;

class Front extends Home
{
    public $metadata;

    public function __construct()
    {
        $this->metadata = [
            'title' => '',
            'meta_title' => __('Heatmap & Sessions Recording Tool'),
            'meta_description' => __('Heatmap & Sessions Recording Tool'),
            'meta_image' =>  'assets/images/meta-image.png',
            'meta_keyword' => 'heatmap,session recording,live user,analytics,seo',
            'meta_author' => 'Xerone IT'
        ];
    }

    public function index(Request $request){
        if(file_exists(public_path("install.txt")))
        {
            \Artisan::call('storage:link');
            $source = base_path('assets');
            $target = public_path('assets');
            if (!file_exists($target)) {
                File::link($source, $target);
            }
                    
            $install_txt_permission = File::isWritable(public_path("install.txt"));
            $env_file_permission = File::isWritable(base_path('.env'));
            $views_file_permission = File::isWritable(base_path('resources/views/component.blade.php'));
            $controllers_file_permission = File::isWritable(base_path('app/Http/Controllers/Member.php'));
            
            $helpers_file_permission = File::isWritable(base_path('app/Helpers/Custom.php'));
            $services_file_permission = File::isWritable(base_path('app/Services/Home.php'));
            $config_file_permission = File::isWritable(base_path('config/app.php'));
            $assets_file_permission = File::isWritable(base_path('assets/css/custom.css'));
            $routes_file_permission = File::isWritable(base_path('routes/web.php'));
            $storage_file_permission = File::isWritable(base_path('storage/app/public/assets/logo/logo.png'));
            $data['body'] = 'front.index_install';
            $data['install_txt_permission'] = $install_txt_permission;
            $data['env_file_permission'] = $env_file_permission;
            $data['views_file_permission'] = $views_file_permission;
            $data['controllers_file_permission'] = $controllers_file_permission;
            $data['helpers_file_permission'] = $helpers_file_permission;
            $data['services_file_permission'] = $services_file_permission;
            $data['config_file_permission'] = $config_file_permission;
            $data['assets_file_permission'] = $assets_file_permission;
            $data['routes_file_permission'] = $routes_file_permission;
            $data['storage_file_permission'] = $storage_file_permission;
        }
        else
        {
            $aff_track = $request->aff_track;
            if($aff_track !== null)
            {
                $affiliate_user_id = hex2bin($aff_track);
                //cookie set for 1 year (525,600 minutes)
                Cookie::queue('affiliate_user_id', $affiliate_user_id, 525600);
            }
            $data = $this->make_view_data();
            if($data['disable_landing_page']=='1') return redirect()->route('login');
            $data['body'] = 'front.index';
            $data['title'] = $data['get_landing_language']->company_title ?? '';
        }
        return $this->site_viewcontroller($data);
    }

    public function installation_submit(Request $request)
    {
        $rules = [];
        $rules['host_name'] = 'required';
        $rules['database_name'] = 'required';
        $rules['database_username'] = 'required';
        
        $rules['app_username'] = 'required|email';
        $rules['app_password'] = 'required';
        $request->validate($rules);

        $host_name = $request->host_name;
        $database_name = $request->database_name;

        $database_username = $request->database_username;
        $database_password = $request->database_password;

        $app_username = $request->app_username;
        $app_password = $request->app_password;
        $institute_name = $request->institute_name;
        $institute_address = $request->institute_address;
        $institute_mobile = $request->institute_mobile;


        $con=@mysqli_connect($host_name, $database_username, $database_password);
        if (!$con) {
            $mysql_error = "Could not connect to MySQL : ";
            $mysql_error .= mysqli_connect_error();

            die($mysql_error);
        }
        if (!@mysqli_select_db($con,$database_name)) {

            die("database not found");
        }

        Config::set('database.connections.mysql.host', $host_name);   
        Config::set('database.connections.mysql.database',  $database_name);
        Config::set('database.connections.mysql.username', $database_username);
        Config::set('database.connections.mysql.password', $database_password);

        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
            $app_url = "https://";   
        else  
            $app_url = "http://";   
        // Append the host(domain name, ip) to the URL.   
        $app_url.= $_SERVER['HTTP_HOST'];   
        // Append the requested resource location to the URL   
        $app_url.= $_SERVER['REQUEST_URI'];    
        $app_url = str_replace('/installation-submit','',$app_url);

        $path = base_path('.env');
        $initial_env = public_path('initial_env.txt');
        $test = file_get_contents($initial_env);
        if (file_exists($path))
        {
            $test = str_replace('DB_HOST=', 'DB_HOST='.$host_name, $test);
            $test = str_replace('DB_DATABASE=', 'DB_DATABASE='.$database_name, $test);
            $test = str_replace('DB_USERNAME=', 'DB_USERNAME='.$database_username, $test);
            $test = str_replace('DB_PASSWORD=', 'DB_PASSWORD='.$database_password, $test);
            $test = str_replace('APP_URL=', 'APP_URL='.$app_url, $test);
            file_put_contents($path,$test);
        }

        $dump_sql_path = public_path('initial_db.sql');
        $dump_file = $this->import_dump($dump_sql_path,$con);
        DB::table('version')->insert(['version'=>trim(env('APP_VERSION')),'current'=>'1','date'=>date('Y-m-d H:i:s')]);
        //generating hash password for admin and updaing database
        $app_password = Hash::make($app_password);
        DB::table('users')->where('user_type','Admin')->update(["mobile" => $institute_mobile, "email" => $app_username, "password" => $app_password, "name" => $institute_name, "status" => "1", "deleted" => "0", "address" => $institute_address]);
        //generating hash password for admin and updaing database

        //deleting the install.txt file,because installation is complete
        if (file_exists(public_path('install.txt'))) {
          unlink(public_path('install.txt'));
        }
        //deleting the install.txt file,because installation is complete
        return redirect('login');
    }

    public function import_dump($filename = '',$con='')
    {
        if ($filename=='') {
            return false;
        }
        if (!file_exists($filename)) {
            return false;
        }
        // Temporary variable, used to store current query
        $templine = '';
        // Read in entire file
        $lines = file($filename);
        // Loop through each line
        foreach ($lines as $line) {
            // Skip it if it's a comment
            if (substr($line, 0, 2) == '--' || $line == '') {
                continue;
            }

            // Add this line to the current segment
            $templine .= $line;
            // If it has a semicolon at the end, it's the end of the query
            if (substr(trim($line), -1, 1) == ';') {

                mysqli_query($con, $templine);
                // Reset temp variable to empty
                $templine = '';
            }
        }
        return true;

    }

    public function pricing_plan(){
        $validity = !empty(request()->validity) ? request()->validity : 30;
        $data = $this->make_view_data();
        $data['body'] = 'front.pricing';
        $data['title'] = __('Pricing Plan');
        $get_pricing_list = $this->get_pricing_list();
        $data['get_modules'] = $this->get_modules();
        $data['other_available_plan_show'] = false;
        $data['format_settings'] = $this->get_payment_formatting_data();
        $package_validity_list = [];
        foreach($get_pricing_list as $key=>$value){
            if(!isset($package_validity_list[$value->validity]) && $value->is_default!='1') $package_validity_list[$value->validity] = convert_number_validity_phrase($value->validity);
            if(!empty($validity) && $value->validity!=$validity && $value->is_default!='1') $get_pricing_list->forget($key);
        }
        ksort($package_validity_list);
        $data['package_validity_list'] = $package_validity_list;
        $data['get_pricing_list'] = $get_pricing_list;
        $data['default_validity'] = $validity;
        return $this->site_viewcontroller($data);
    }

    public function dashboard(){
        if($this->is_user()) abort(403);
        $data = $this->make_view_data();

        $select=array("blog_comments.*","name","user_type","profile_pic","blog_title","blog_slug");
        $parent_comment_info = DB::table('blog_comments')->select($select)
            ->where(['display_admin_dashboard'=>"1","hidden"=>"0","users.status"=>"1"])
            ->whereNull('parent_blog_comment_id')
            ->leftJoin('users','blog_comments.user_id','=','users.id')
            ->leftJoin('blogs','blog_comments.blog_id','=','blogs.id')
            ->orderByRaw('blog_comments.updated_at DESC')->paginate(20);

        $parent_ids=array();
        foreach ($parent_comment_info as $key => $value)
        {
            array_push($parent_ids, $value->id);
        }
        $parent_ids=array_unique($parent_ids);
        $total_comment = count($parent_comment_info);
        $child_comment_info_formatted=array();
        if(!empty($parent_ids)){
            $select=array("blog_comments.*","name","user_type","profile_pic");
            $child_comment_info = DB::table("blog_comments")->select($select)
                ->where(["hidden"=>"0","users.status"=>"1"])
                ->whereIntegerInRaw('parent_blog_comment_id',$parent_ids)
                ->leftJoin('users','blog_comments.user_id','=','users.id')
                ->orderByRaw('blog_comments.updated_at ASC')->get();
            foreach ($child_comment_info as $key => $value)
            {
                $child_comment_info_formatted[$value->parent_blog_comment_id][]=$value;
            }
        }

        $data['popular_blog_info'] = $this->get_popular_blog_list(20);

        $data["total_comment"]=count($parent_comment_info);
        $data["parent_comment_info"]=$parent_comment_info;
        $data["child_comment_info"]=$child_comment_info_formatted;

        $data['title'] = $data['meta_title'] = __('Comment Dashboard');
        $data['is_user'] = $this->is_user();
        $data['body'] = 'front/blog/dashboard';
        return $this->site_viewcontroller($data);
    }

    public function create_blog(){
        if($this->is_user()) abort(403);
        $data = $this->make_view_data();
        $data['title'] = __('New Blog');
        $data['body'] = 'front/blog/create-blog';
        $data['category_list'] = $this->get_category_list();
        return $this->site_viewcontroller($data);
    }

    public function update_blog($blog_id=0){
        if($this->is_user()) abort(403);
        $data = $this->make_view_data();
        $data['title'] = __('Update Blog');
        $data['body'] = 'front/blog/create-blog';
        $data['category_list'] = $this->get_category_list();
        $data['xdata'] = DB::table('blogs')->where('id',$blog_id)->first();
        return $this->site_viewcontroller($data);
    }

    public function save_blog(Request $request)
    {
        if($this->is_user()) abort(403);
        $id = (int) $request->id;
        $blog_slug = $request->blog_slug;
        $user_id = Auth::user()->id;
        $rules =
            [
                'blog_category_id' => 'nullable|sometimes',
                'blog_title' => 'required|string',
                'blog_content' => 'required|string',
                'blog_keyword' => 'nullable|sometimes',
                'blog_img'=>'nullable|sometimes|image|mimes:png,jpg,jpeg,webp|max:500',
                'blog_slug'=>'required'
            ];
        if($id==0) $rules['blog_slug'] = 'required|string|unique:blogs,blog_slug';

        $validate_data = $request->validate($rules);
        if($id>0 && isset($validate_data['blog_slug'])) unset($validate_data['blog_slug']);

        $validate_data['status'] = isset($_POST['status']) ? "1" : "0";
        if($id==0) {
            $validate_data['user_id'] = $user_id;
            $validate_data['updated_at'] = date('Y-m-d H:i:s');
        }

        if($request->file('blog_img')) {

            $file = $request->file('blog_img');
            $extension = $request->file('blog_img')->getClientOriginalExtension();
            $filename = $blog_slug.'.'.$extension;
            $upload_dir_subpath = 'public/blog';

            if(env('AWS_UPLOAD_ENABLED')){
               try {
                   $upload2S3 = Storage::disk('s3')->putFileAs('blog', $file,$filename);
                   $validate_data['blog_img'] = Storage::disk('s3')->url($upload2S3);
               }
               catch (\Exception $e){
                   $error_message = $e->getMessage();
               }
            }
            else{
                $request->file('blog_img')->storeAs(
                    $upload_dir_subpath, $filename
                );
                $validate_data['blog_img'] = asset('storage/blog').'/'.$filename;
            }
        }

        $validate_data["blog_content"] = str_replace('<img src="','<img class="img-fluid" src="',$validate_data['blog_content']);

        $query = true;
        if($id>0) DB::table("blogs")->where(['id'=>$id])->update($validate_data);
        else $query = DB::table("blogs")->insert($validate_data);

        if($query) $request->session()->flash('save_blog_status', __('1'));
        else $request->session()->flash('save_blog_status', __('0'));

        if($id>0) return redirect(route('single-blog',$blog_slug));
        else return redirect(route('list-blog'));

    }

    public function blog_list(Request $request){

        if(isset($_POST['search'])) {
            $search = $request->search;
            session(['blog_seacrh_param'=>$search]);
        }
        $search = session('blog_seacrh_param');

        $data = $this->make_view_data();
        $data['title'] = __('Blog');
        $data['meta_keyword'] = $data['meta_keyword'].',blog,how to,update,news,latest';

        $query = DB::table('blogs');
        if(!empty($search)) $query->whereRaw("MATCH(blog_content) AGAINST(?)", array($search));
        if(Auth::user() && !in_array(Auth::user()->user_type,['Admin','Manger'])) $query->where('status','1');
        $query = $query->orderByRaw('id DESC');
        $data['blog_list'] = $query->paginate(12);
        $data['body'] = 'front/blog/list-blog';
        return $this->site_viewcontroller($data);
    }

    public function blog_single($blog_slug=''){
        $data = $this->make_view_data();
        $select=array("blogs.*","blogs.id as blog_id","category_name","category_slug","users.name as author_name","users.profile_pic as author_img");
        $query = DB::table('blogs')->select($select)->where('blog_slug',$blog_slug)
            ->leftJoin('blog_categories','blogs.blog_category_id','=','blog_categories.id')
            ->leftJoin('users','blogs.user_id','=','users.id');
        if(Auth::user() && !in_array(Auth::user()->user_type,['Admin','Manger'])) $query->where('blogs.status','1');
        $blog_info = $query->first();
        if(!$blog_info) abort(404);

        DB::statement("UPDATE `blogs` SET `view_count` = view_count+1 WHERE `blog_slug` = '".$blog_slug."'"); // increasing view count

        $data['popular_blog_info'] = $this->get_popular_blog_list(5);

        $query = DB::table('blogs')
            ->whereRaw("MATCH(blog_content) AGAINST(?)", array($blog_info->blog_title))->where('status','1')->where('blogs.id','!=',$blog_info->id);
        $data['related_blog_info'] = $query->orderByRaw('view_count DESC')->limit(3)->get();

        $select=array("blog_comments.*","name","user_type","profile_pic");
        $parent_comment_info = DB::table('blog_comments')->select($select)
            ->where(['blog_comments.blog_id'=>$blog_info->id,"hidden"=>"0","users.status"=>"1"])
            ->whereNull('parent_blog_comment_id')
            ->leftJoin('users','blog_comments.user_id','=','users.id')
            ->orderByRaw('blog_comments.updated_at DESC')->paginate(20);
        $parent_ids=array();
        foreach ($parent_comment_info as $key => $value)
        {
            array_push($parent_ids, $value->id);
        }
        $parent_ids=array_unique($parent_ids);
        $total_comment=count($parent_comment_info);
        $child_comment_info_formatted=array();
        if(!empty($parent_ids))
        {
            $child_comment_info = DB::table("blog_comments")->select($select)
                ->where(['blog_comments.blog_id'=>$blog_info->id,"hidden"=>"0","users.status"=>"1"])
                ->whereIntegerInRaw('parent_blog_comment_id',$parent_ids)
                ->leftJoin('users','blog_comments.user_id','=','users.id')
                ->orderByRaw('blog_comments.updated_at ASC')->get();
            foreach ($child_comment_info as $key => $value)
            {
                $total_comment++;
                $child_comment_info_formatted[$value->parent_blog_comment_id][]=$value;
            }
        }
        $data["total_comment"]=$total_comment;
        $data["parent_comment_info"]=$parent_comment_info;
        $data["child_comment_info"]=$child_comment_info_formatted;

        $blog_content = strip_tags(html_entity_decode($blog_info->blog_content));
        $blog_content = str_replace(array('"',"'","\n"),array('','',' '), $blog_content);
        $blog_content = substr($blog_content,0,300);
        $data['title'] = $blog_info->blog_title;
        $data['meta_title'] = $blog_info->blog_title;
        $data['meta_description'] = $blog_content;
        $data['meta_keyword'] = $blog_info->blog_keyword;
        if(!empty($blog_info->blog_img)) $data['meta_image'] = $blog_info->blog_img;
        $data['is_user'] = $this->is_user();
        $data['blog_info'] = $blog_info;
        $data['body'] = 'front/blog/single-blog';
        return $this->site_viewcontroller($data);
    }

    public function comment_single($comment_id=''){
        $data = $this->make_view_data();
        $select=array("blog_comments.*","name","user_type","profile_pic","blog_title","blog_slug");
        $parent_comment_info = DB::table('blog_comments')->select($select)
            ->where(['blog_comments.id'=>$comment_id,"hidden"=>"0","users.status"=>"1"])
            ->whereNull('parent_blog_comment_id')
            ->leftJoin('users','blog_comments.user_id','=','users.id')
            ->leftJoin('blogs','blog_comments.blog_id','=','blogs.id')
            ->paginate(1);
        if($parent_comment_info->isEmpty()) abort(404);

        $child_comment_info_formatted=array();

        $select=array("blog_comments.*","name","user_type","profile_pic");
        $child_comment_info = DB::table("blog_comments")->select($select)
            ->where(['blog_comments.parent_blog_comment_id'=>$comment_id,"hidden"=>"0","users.status"=>"1"])
            ->leftJoin('users','blog_comments.user_id','=','users.id')
            ->orderByRaw('blog_comments.updated_at ASC')->get();
        $total_comment = 1;
        foreach ($child_comment_info as $key => $value)
        {
            $total_comment++;
            $child_comment_info_formatted[$value->parent_blog_comment_id][]=$value;
        }

        $data['popular_blog_info'] = $this->get_popular_blog_list(5);

        $data["total_comment"]=$total_comment;
        $data["parent_comment_info"]=$parent_comment_info;
        $data["child_comment_info"]=$child_comment_info_formatted;

        $data['title'] = "#".$comment_id;
        $data['meta_title'] = $parent_comment_info->first()->blog_title ? $parent_comment_info->first()->blog_title.' : '.__('Blog Comment').$data['title'] : $data['title'];
        $data['is_user'] = $this->is_user();
        $data['body'] = 'front/blog/single-comment';
        return $this->site_viewcontroller($data);
    }

    public function comment_reply(Request $request){
        $blog_id = $request->blog_id;
        $parent_blog_comment_id = $request->parent_blog_comment_id;
        $comment = $request->comment;
        $parent_commenter_id = $request->parent_commenter_id;
        $display_admin_dashboard = $parent_blog_comment_id==0 && $this->is_user() ? '1' : '0'; // new comment from user entry in dashboard
        $user_id = Auth::user()->id;
        $insert_data = [
            'user_id' => $user_id,
            'blog_id' => $blog_id,
            'comment' => $comment,
            'updated_at' => date('Y-m-d H:i:s'),
            'display_admin_dashboard' => $display_admin_dashboard
        ];
        if($parent_blog_comment_id>0) $insert_data['parent_blog_comment_id'] = $parent_blog_comment_id;
        DB::table('blog_comments')->insert($insert_data);
        $comment_id = DB::getPdo()->lastInsertId();

        // mark parent comment to show in admin dashboard if its a comment reply by user
        if($this->is_user() && $parent_blog_comment_id>0)
        DB::table('blog_comments')->where('id',$parent_blog_comment_id)->update(['display_admin_dashboard'=>'1']);

        // mark parent comment to hide from admin dashboard if its a comment reply by admin/team/manager
        if(!$this->is_user() && $parent_blog_comment_id>0)
        DB::table('blog_comments')->where('id',$parent_blog_comment_id)->update(['display_admin_dashboard'=>'0']);

        $username = Auth::user()->name;
        $avatar = !empty(Auth::user()->profile_pic) ? Auth::user()->profile_pic : asset('assets/images/avatar/avatar-' . rand(1, 5) . '.png');
        $single_comment_url = route('single-comment',$comment_id);
        $hide_reply_button = Auth::user()->enable_blog_comment=='0' ? 'd-none' : '';

        $hide_button = $mark_seen_button = '';
        if(Auth::user()->user_type=='Admin') $hide_button = '<a href="#" data-id="'.$comment_id.'"  class="hide_comment btn btn-sm btn-outline-danger me-2" title="'.__('Remove').'"><i class="fas fa-trash-alt"></i></a>';
        $html = '';
        if($parent_blog_comment_id==0){
            if(!$this->is_user() && $display_admin_dashboard=='1')
                $mark_seen_button = '<a href="#" data-id="'.$comment_id.'" class="seen_comment btn btn-sm btn-outline-secondary me-2" title="'.__('Mark as Seen').'"><i class="fas fa-eye"></i></a>';
            $html = '
            <li class="border-bottom-0 align-items-start" id="comment-'.$comment_id.'">
               <div class="ud-article-image">
                 <img src="'.$avatar.'" alt="author" class="h-100">
               </div>
               <div class="ud-article-content w-100">
                   <h6 class="d-inline">
                       <a class="text-dark" href="'.$single_comment_url.'">
                           '.$username.'
                       </a>
                   </h6>
                   <a class="text-muted small" href="'.$single_comment_url.'">
                       <i class="far fa-clock"></i> '.__("Now").'
                   </a>
                  <div class="d-inline float-end '.$hide_reply_button.'">
                      '.$hide_button.$mark_seen_button.'
                      <a href="#" data-id="'.$comment_id.'" data-blog-id="'.$blog_id.'" data-parent-commenter-id="'.$user_id.'" class="reply_comment btn btn-sm btn-primary text-white" title="'.__("Reply").'"><i class="far fa-comment"></i></a>
                  </div>
                   <p class="ud-article-author mt-2">'.format_comment($comment).'</p>

                   <ul class="ud-articles-list">
                       <div class="append_comment" id="append_comment-'.$comment_id.'">
                       </div>
                   </ul>
               </div>

           </li>';
        }
        else{
            $html = '
            <li class="border-bottom-0 align-items-start pb-0">
               <div class="me-3">
                   <img src="'.$avatar.'" alt="author" class="rounded-circle mt-1" style="width: 40px;height: 40px;max-width: 40px;">
               </div>
               <div class="ud-article-content w-100">
                   <span class="text-dark">'.$username.'</span>
                   <span class="text-muted small"><i class="far fa-clock"></i> '.__("Now").' </span>
                   <div class="d-inline float-end">
                   '.$hide_button.'
                   </div>
                   <p class="ud-article-author mt-1">'.format_comment($comment).'</p>
               </div>
            </li>';
        }
        echo $html;
    }

    public function comment_hide(Request $request){
        if(Auth::user()->user_type !='Admin'){
            echo json_encode(['success'=>'0','message'=>__('Access denied.')]);
            exit();
        }
        $comment_id = $request->comment_id;
        DB::table('blog_comments')->where('id',$comment_id)->update(['hidden'=>'1','hidden_by'=>Auth::user()->id,'hidden_at'=>date('Y-m-d H:i:s')]);
        echo json_encode(['success'=>'1','message'=>__('Comment has been removed successfully.')]);
    }

    public function comment_seen(Request $request){
        if($this->is_user()){
            echo json_encode(['success'=>'0','message'=>__('Access denied.')]);
            exit();
        }
        $comment_id = $request->comment_id;
        DB::table('blog_comments')->where('id',$comment_id)->update(['display_admin_dashboard'=>'0']);
        echo json_encode(['success'=>'1','message'=>__('Comment has been marked as seen successfully.')]);
    }

    public function delete_blog(Request $request){
        if(Auth::user()->user_type != 'Admin'){
            echo json_encode(['success'=>'0','message'=>__('Access denied.')]);
            exit();
        }
        $blog_id = $request->blog_id;
        DB::table('blogs')->where('id',$blog_id)->delete();
        echo json_encode(['success'=>'1','message'=>__('Blog has been marked as deleted successfully.')]);
    }

    public function policy_privacy(){
        $data = $this->make_view_data();
        $data['body'] = 'front.policy.privacy';
        $data['title'] = __('Privacy Policy');
        return $this->site_viewcontroller($data);
    }

    public function policy_terms(){
        $data = $this->make_view_data();
        $data['body'] = 'front.policy.terms';
        $data['title'] = __('Terms of Service');
        return $this->site_viewcontroller($data);
    }

    public function policy_refund(){
        $data = $this->make_view_data();
        $data['body'] = 'front.policy.refund';
        $data['title'] = __('Refund Policy');
        return $this->site_viewcontroller($data);
    }

    public function policy_gdpr(){
        $data = $this->make_view_data();
        $data['body'] = 'front.policy.gdpr';
        $data['title'] = __('GDPR');
        return $this->site_viewcontroller($data);
    }

    public function accept_cookie(){
        session(['allow_cookie'=>'yes']);
    }

    protected function is_user(){
        if(!Auth::user()) return false;
        if(Auth::user()->user_type=='Admin') return false;
        if(Auth::user()->user_type=='Manager' && in_array(Auth::user()->parent_user_id,[0,1])) return false;
        return true;
    }

    protected function get_category_list(){
        $result =  DB::table('blog_categories')->where(['status'=>'1','deleted'=>'0'])->orderByRaw('category_name ASC')->get();
        $return = [''=>__('Select Category')];
        foreach ($result as $item) {
            $return[$item->id] = $item->category_name;
        }
        return $return;
    }

    protected function get_popular_blog_list($limit=5){
        $select=array("blog_slug","blog_title","blog_title","blog_img","blog_content","blogs.updated_at","users.name as author_name","users.profile_pic as author_img");
        $query = DB::table('blogs')->select($select)->where('blogs.status','1')
            ->leftJoin('users','blogs.user_id','=','users.id');
        return $query->orderByRaw('view_count DESC')->limit($limit)->get();
    }

    protected function get_pricing_list($limit=9999){
        $user_id = get_agent_id();
        if(empty($user_id)) $user_id = 1;
        $query = DB::table('packages')->where(['user_id'=>$user_id,'visible'=>'1','deleted'=>'0']);
        $result = $query->orderByRaw('CAST(`price` AS SIGNED)')->limit($limit)->get();
        return $result;
    }

}
