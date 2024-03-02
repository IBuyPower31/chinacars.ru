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
        $manufacturer_id = $this->request->post['manufacturer_id'];
        $model_name = $this->request->post['model_name'];

        $models_info = $this->model_catalog_product->getComplectation($manufacturer_id, $model_name);
        $data = array();
        foreach ($models_info as $item) {
            $data[] = $item['type'];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }
}
