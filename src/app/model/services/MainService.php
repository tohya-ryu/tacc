<?php

class MainService implements FrameworkServiceBase {
    use FrameworkMagicGet;
    private static $magic_get_attr = array(
         'controller'
    );

    private $controller;
    private $validator;
    private $db;

    public $find_result;

    public function __construct($controller)
    {
        $this->controller = $controller;
        $this->validator = new FrameworkValidator();
        $this->db = FrameworkStoreManager::get()->store();
        $this->find_result = null;
    }

    public function find_validate()
    {
        $req = FrameworkRequest::get();
        $this->validator->validate($req->param->post('find-string'));
        $this->validator->validate($req->param->post('find-type'));
        $this->validator->required();
        return $this->validator->is_valid();
    }

    public function find_data()
    {
        $req = FrameworkRequest::get();
        $str = $req->param->post('find-string')->value;
        // set $this->find_result
        $service = null;
        switch ($req->param->post('find-type')->value) {
        case 1: # vocab - kanji_name
            $service = new VocabService($this->controller);
            $service->lookup('kanji_name', 'LIKE', $str);
            break;
        case 2: # vocab - hiragana_name
            $service = new VocabService($this->controller);
            $service->lookup('hiragana_name', 'LIKE', $str);
            break;
        case 3: # vocab - meanings
            $service = new VocabService($this->controller);
            $service->lookup('meanings', 'LIKE', $str);
            break;
        case 4: # vocab - tags
            $service = new VocabService($this->controller);
            $service->lookup('tags', 'LIKE', $str);
            break;
        case 5: # kanji - kanji
            $service = new KanjiService($this->controller);
            $service->lookup('kanji', '=', $str);
            break;
        case 6: # kanji - onyomi
            $service = new KanjiService($this->controller);
            $service->lookup('onyomi', 'LIKE', $str);
            break;
        case 7: # kanji - kunyomi
            $service = new KanjiService($this->controller);
            $service->lookup('kunyomi', 'LIKE', $str);
            break;
        case 8: # kanji - meanings
            $service = new KanjiService($this->controller);
            $service->lookup('meanings', 'LIKE', $str);
            break;
        case 9: # kanji - tags
            $service = new KanjiService($this->controller);
            $service->lookup('tags', 'LIKE', $str);
            break;
        }
        $this->find_result = $service->get_lookup_result();
    }

}
