<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use ZipArchive;

class UpdateSystem extends Home
{
    public function __construct()
    {
        $this->set_global_userdata(true,['Admin']);
    }
    public function index()
    {
        $data= $this->update_list();
        return $this->viewcontroller($data);

    }

    public function update_list_v2()
    {
        $product_id = $this->app_product_id;
        $current_version = DB::table('version')->where('current','1')->first();

        $server_url='https://xeroneit.solutions';
        
        if(isset($current_version)) :           
            $product_version = $current_version->version;
        else :
            $product_version =1.0;
        endif;

        $purchase_code="";
        if(file_exists(base_path('config/licence.txt')))
        {
            $file_data = file_get_contents(base_path('config/licence.txt'));
            $file_data_array = json_decode($file_data, true);
            $purchase_code = isset($file_data_array['purchase_code']) ? $file_data_array['purchase_code'] : "";
        }

        $data = array('product' => $product_id, 'version' => $product_version, 'purchase_code' => $purchase_code);

        $string = '';
        foreach($data as $index => $value)
        {
            $string .= "$index=$value&";
        }
        $string = trim($string, '&');

        $ch = curl_init($server_url.'/development/version_control/project_versions_api/return_check_updates/');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_REFERER,$_SERVER['SERVER_NAME']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3"); 
        $response = curl_exec($ch);
        $res = curl_getinfo($ch);

        if($res['http_code'] != 200)
        {
            echo "<h2 style='color: red'>Connection failed to establish, cURL is not working! Visit item description page in codecanyon, see change log and update manually.</h2>";
            exit();
        }

        curl_close($ch);

       
        /** Delete all previous record **/
        DB::table('update_list')->where('id','>=',1)->delete();
        $updated_version_bd_insert=json_decode($response);
        if(isset($updated_version_bd_insert[0])){
            $insert_files = $updated_version_bd_insert[0]->f_source_and_replace;
            $insert_sql = json_encode(explode(';',$updated_version_bd_insert[0]->sql_cmd));
            $insert_update_id = $updated_version_bd_insert[0]->id;
            DB::table('update_list')->insert([
                'files'=>$insert_files,
                'sql_query'=>$insert_sql,
                'update_id'=>$insert_update_id
            ]);

        }

        $data['current_version'] = $product_version;
        $data['update_versions'] = json_decode($response);
        $data['body']='update_system.index_v2';
        $data['page_title']=__("Check Update");

        return $this->viewcontroller($data);
    }
    public function initialize_update(Request $request)
    {
        
        if(!class_exists('ZipArchive'))
        {
            if(!isset($response))
            {
                $response=array('status'=>'0','message'=>'ZipArchive is not working! See log and update manually.');
                echo json_encode($response);
                exit();
            }
        }

        $update_version_id = $request->update_version_id;
        $version = $request->version;
        /*** Get file & Sql information from Database ***/
        $file_sql_info = DB::table('update_list')->where('update_id',$update_version_id)->first();
        $files = json_decode($file_sql_info->files,TRUE);
        $sql = json_decode($file_sql_info->sql_query,TRUE);
        
        $files_replaces = $files;

        try 
        {
            if(file_exists(' application')) @$this->delete_directoryX(' application');
            if(file_exists('modules')) @$this->delete_directoryX('modules');
            if(file_exists('application/views/site/CustomTheme') && !file_exists('application/views/site/CustomTheme/index.php')) @$this->delete_directoryX('application/views/site/CustomTheme');

            if(count($files_replaces) > 0) :
                foreach($files_replaces as $file) :
                    $url = $file[0];
                    $replace = $file[1];
                    $file_name = explode('-', $url);
                    $file_name = end($file_name);

                    $is_delete = $file[2];
                    if($is_delete == '1')
                    {
                        if(is_file($replace))
                        {
                            unlink($replace);
                        }
                        else
                        {
                            $delete_folder_path = $replace;
                            $last_folder = explode('.', $file_name);
                            $last_folder = $last_folder[0];
                            $delete_folder_path = $delete_folder_path . $last_folder;
                            // last positin: only folder name don't need /
                            $this->delete_directoryX($delete_folder_path);
                        }
                    }

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3"); 
                    $return = curl_exec($ch);
                    curl_close($ch);
                    if (!file_exists(storage_path('app/public/download/'.$version))) {
                        mkdir(storage_path('app/public/download/'.$version), 0755, true);
                    }
                    $destination = storage_path('app/public/download/'.$version.'/'.$file_name);
                    $file = fopen($destination, 'w');
                    fputs($file, $return);
                    fclose($file);

                    if(strpos($file_name, '.zip') != false) :
                        $folder_path = $replace;

                        if (!file_exists($folder_path)) {
                            mkdir($folder_path, 0755, true);
                        }

                        $zip = new ZipArchive;
                        $res = $zip->open($destination);
                        if ($res === TRUE) :
                            $zip->extractTo($replace);
                            $zip->close();
                        endif;
                    else :
                        $current = file_get_contents($destination, true);
                        $last_pos = strrpos($replace, '/');
                        $folder_path = substr($replace, 0, $last_pos);

                        if (!file_exists($folder_path) && $folder_path!="") {
                            mkdir($folder_path, 0755, true);
                        }

                        $replace_file = fopen($replace, 'w');   
                        fputs($replace_file, $current);
                        fclose($replace_file);                  
                    endif;
                endforeach;         
            endif;
            if(is_array($sql)) :
                $sql_cmd_array = $sql;
                foreach($sql_cmd_array as $single_cmd) :
                        $semicolon = ';';
                        $ex_sql = $single_cmd . $semicolon;

                        if(strlen($ex_sql) > 1) :
                            try { 
                                DB::statement($ex_sql);
                            } catch(\Illuminate\Database\QueryException $ex){ 
                                $error = $ex->getMessage();
                            }
                        endif;
                endforeach;
            endif;

            $this->delete_directoryX(storage_path('app/public/download/'.$version));


            if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
                $app_url = "https://";   
            else  
                $app_url = "http://";   
            // Append the host(domain name, ip) to the URL.   
            $app_url.= $_SERVER['HTTP_HOST'];   
            // Append the requested resource location to the URL   
            $app_url.= $_SERVER['REQUEST_URI'];    
            $app_url = str_replace('/update_system/initialize_update','',$app_url);

            $env_file = base_path('.env');
            $initial_env_file = public_path('initial_env.txt');
            $env_file_data = file_get_contents($env_file);
            $initial_env_file_data = file_get_contents($initial_env_file);
            if (file_exists($env_file))
            {
                $modified_env_file_data = str_replace('APP_URL=http://heatsketch.test', 'APP_URL='.$app_url, $env_file_data);
                file_put_contents($env_file,$modified_env_file_data);

                $modified_initial_env_file_data = str_replace('APP_URL=http://heatsketch.test', 'APP_URL='.$app_url, $initial_env_file_data);
                file_put_contents($initial_env_file,$modified_initial_env_file_data);
            }


            $response=array('status'=>'1','message'=>__('app has been updated successfully.'));
            
        } 

        catch(Exception $e)
        {
            $error= $e->getMessage();
            if(!isset($response))
            {
                $response=array('status'=>'0','message'=>$error);
            }            
        }

        echo json_encode($response);

    }
}
