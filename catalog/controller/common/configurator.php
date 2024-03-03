<?php

class ControllerCommonConfigurator extends Controller
{

    public function index()
    {
        $this->load->model('catalog/product');
        $data = array();
        $manufacturer_info = $this->model_catalog_product->getManufacturers();
        $data['marks'] = array();
        foreach ($manufacturer_info as $item) {
            $data['marks'][] = array(
                'manufacturer_id' => $item['manufacturer_id'],
                'name'             => $item['name'],
            );
        }
        //  Получаем все изображения, чтобы запихнуть их в Slider
        $results = $this->model_catalog_product->getAllProducts();

        $images = array();
        foreach ($results as $result){
            if ($result['image']){
                $thumb = $this->model_tool_image->resize($result['image'], 520, 520);
            }
            else{
                $thumb = 'no-image.png';
            }
            $images[] = $thumb;
        }
        $data['images'] = $images;

        $this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
        $this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
        $this->document->addStyle('catalog/view/javascript/jquery/swiper/css/swiper.min.css');
        $this->document->addScript('catalog/view/javascript/jquery/swiper/js/swiper.jquery.js');
        $this->document->addScript('https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js');
        $this->document->addStyle('https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css');


        return $this->load->view('common/configurator', $data);
    }

    public function getModels(){
        $this->load->model('catalog/product');
        $manufacturer_id = $this->request->post['manufacturer_id'];
        $models_info = $this->model_catalog_product->getModels($manufacturer_id);
        $data = array();
        foreach ($models_info as $item) {
            $data[] = $item['location'];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function getComplectation(){
        $this->load->model('catalog/product');
        $query['manufacturer_id'] = $this->request->post['manufacturer_id'];
        $query['model_name'] = $this->request->post['model_name'];
        $models_info = $this->model_catalog_product->getComplectation($query);
        $data = array();
        foreach ($models_info as $item) {
            $data[] = $item['type'];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function getCar(){
        $this->load->model('catalog/product');
        $this->load->model('tool/image');
        $query['manufacturer_id'] = $this->request->post['manufacturer_id'];
        $query['model_name'] = $this->request->post['model_name'];
        $query['type'] = $this->request->post['type'];

        $result = $this->model_catalog_product->getCar($query);

        if ($result['image']){
            $thumb = $this->model_tool_image->resize($result['image'], 500, 500);
        }
        else{
            $thumb = 'no-image.png';
        }

        $product = array(
            'product_id' => $result['product_id'],
            'price'      => $this->currency->format($result['price'], $this->config->get('config_currency')),
            'image'      => $thumb,
        );
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($product));
    }

}
