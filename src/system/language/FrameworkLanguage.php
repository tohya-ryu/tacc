<?php

class FrameworkLanguage {
    use FrameworkSingleton;
    use FrameworkMagicGet;
    private static $magic_get_attr = array(
        'tag'
    );

    private $tag;

    private final function __construct()
    {
    }

    public function from_default()
    {
        $this->tag = AppConf::get('default_language');
    }

    public function from_browser()
    {
        $request = FrameworkRequest::get();
        if (!is_null($request->acclang)) {
            foreach ($request->acclang as $needle) {
                if (array_key_exists($needle[0],
                    AppConf::get('available_languages')))
                {
                    $this->tag = 
                        AppConf::get('available_languages')[$needle[0]];
                    break;
                }
            }
        }
    }

    public function from_cookie()
    {
        $request = FrameworkRequest::get();
        $cookie = $request->cookies['framework-language'];
        if ($cookie->state) {
            $this->tag = $cookie->value;
        } else {
            $cookie->set($this->tag);
        }
    }

    public function update($tag)
    {
        $this->tag = $tag;
        $request = FrameworkRequest::get();
        $cookie = $request->cookies['framework-language'];
        $cookie->set($this->tag);
    }

}
