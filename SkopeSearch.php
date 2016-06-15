<?php
require_once 'base_request.php';
class SkopeSearch extends SkopeBaseRequest {

/**
     * 上传图片建立索引库
     * @$images is an array list of array
     *  array(
     *      array("im_name"=>"00001","im_url"=>"00001"),
     *      array("im_name"=>"00002","im_url"=>"00002"),
     *      array("im_name"=>"00003","im_url"=>"00003"),
     *      array("im_name"=>"00004","im_url"=>"00004")
     *  );
     */
    function insert($images = array()) {
        $i = 0;
        $params = array();
        foreach ($images as $image){
            foreach ($image as $key => $value) {
                $param_key = $key . "[" . $i . "]";
                $params[$param_key] = $value;
            }
            $i++;
        }
        return $this->post('insert', $params);
    }

    //更新图片
    
    function update($images = array()) {
        $i = 0;
        $params = array();
        foreach ($images as $image) {
            foreach ($image as $key => $value) {
                $param_key = $key . "[" . $i . "]";
                $params[$param_key] = $value;
            }
            $i++;
        }
        return $this->post('insert', $params);
    }

    //删除图片
    
    function remove($im_names = array()) {
        $params = array();
        $i = 0;
        foreach ($im_names as $im_name) {
            $key = "im_names[" . $i . "]";
            $params[$key] = $im_name;
            $i++;
        }

        return $this->post('remove', $params);
    }

    //上传状态查询
    
    function insert_status($trans_id = '') {
        return $this->get('insert/status/' . $trans_id);
    }

    //
    // 初始认证
    //
    function __construct($access_key = NULL, $secret_key = NULL) {
        parent::__construct($access_key, $secret_key);
    }

    //内部搜索
    
    function idsearch($im_name = NULL, $page = 1, $limit = 20, $fl = array(), $fq = array(), $get_all_fl = false, $score = true, $score_max = 1, $score_min = 0) {
        $params = array(
            'im_name' => $im_name,
            'score' => $score,
            'page' => $page,
            'limit' => $limit,
            'fq' => $fq,
            'fl' => $fl,
            'score_max' => $score_max,
            'score_min' => $score_min,
            'get_all_fl' => $get_all_fl
        );
        return $this->get('search', $params);
    }

    //颜色搜索
    
        function colorsearch($color = NULL, $page = 1, $limit = 20, $fl = array(), $fq = array(), $get_all_fl = false, $score = true, $score_max = 1, $score_min = 0) {
        $params = array(
            'color' => $color,
            'score' => $score,
            'page' => $page,
            'limit' => $limit,
            'fq' => $fq,
            'fl' => $fl,
            'score_max' => $score_max,
            'score_min' => $score_min,
            'get_all_fl' => $get_all_fl
        );
        return $this->get('colorsearch', $params);
    }
    
    //上传搜索
    
    function uploadsearch($image = NULL, $page = 1, $limit = 20, $fl = array(), $fq = array(), $get_all_fl = false, $score = false, $score_max = 1, $score_min = 0, $detection = NULL) {
        if (!gettype($image) == 'object' || !get_class($image) == 'Image')
            throw new SkopeException('Need to pass an image object');

        $params = array(
            'score' => $score,
            'page' => $page,
            'limit' => $limit,
            'fq' => $fq,
            'fl' => $fl,
            'score_max' => $score_max,
            'score_min' => $score_min,
            'get_all_fl' => $get_all_fl,
            'detection' => $detection
        );
        $box = $image->get_box();
        if (!empty($box)) {
            $params["box"] = $image->get_box_parse();
        }
        if ($image->is_http_image()){
            $params["im_url"] = $image->get_path();
            return $this->get('uploadsearch', $params);
        } else {
            $params['image'] = "@{$image->local_filepath}";
            return $this->post_multipart('uploadsearch', $params);
        }
    }

}

?>
