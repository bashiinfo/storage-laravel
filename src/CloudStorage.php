<?php
namespace Bashi;
use GuzzleHttp\Client;
use function GuzzleHttp\json_decode;
class CloudStorage{
    private $appId='';
    private $appKey='';
    private $host = '';
    private $httpClient=null;
    public function __construct($host,$appId,$appKey,$timeOut=2000){
        $this->appId =$appId;
        $this->appKey=$appKey;
        $this->host=$host;
        $this->httpClient=NEW Client([
            'base_uri'=>$host,
            'timeout'=>$timeOut
        ]);
    }
    public function upload($fileContent,$options){
        $fileName = $options['file_name'];
        $options = [
            'type'=>$options['type']?:'',
            'path'=>$options['path']?:'',
            'file_name'=>$fileName
        ];
        // 生成签名
        $options['time']=time();
        $options['appId'] = $this->appId;
        $sign = Sign::genereate($this->appKey, $options);
        $options['sign'] = $sign;
        $mul = [];
        foreach ($options as $k=>$option){
            $mul[]=[
                'name'=>$k,
                'contents'=>$option
            ];
        }
        $mul[] = [
            'name'=>'file',
            'contents'=>$fileContent,
            'file_name'=>$fileName
        ];
        // 开始上传
        try{
            $response = $this->httpClient->request('POST','/api/upload',['multipart'=>$mul]);
        }catch(\Exception $e){
            return [
                'code'=>-1,
                'msg'=>'远程错误!',
                'data'=>null
            ];
        }
        try{
            return json_decode($response->getBody()->getContents(),TRUE);
        }catch(\Exception $e){
            return [
                'code'=>-1,
                'msg'=>'远程错误!',
                'data'=>null
            ];
        }
        
    }
    public function getConfig(){
        // 生成签名
        $options=[
            'time'=>time()
        ];
        $options['sign']=Sign::genereate($this->appKey, $options);
        // 开始获取
        $response = $this->httpClient->get('/api/config',['query'=>$options]);
        // 返回结果
        return json_decode($response->getBody(),TRUE);
    }
}