<?php

namespace Aman5537jains\ReportBuilder\Inputs;

class ReportInputs
{
    public $name = 'name';
    public $title = 'name';
    public $required = '0';
    public $hidden = '0';

    public $value = '';
    public $config = [];
    public $settings = [];

    public function __construct($config, $setting)
    {
        $this->name = $config['name'];

        if (isset($config['hidden'])) {
            $this->hidden = $config['hidden'];
        }

        if (isset($config['required'])) {
            $this->required = $config['required'];
        }

        $this->settings = $setting;
        // dd($this->settings);
        $this->config = $config;
        $params = request()->get('parameters', []);

        if (!isset($this->config['title']) || $this->config['title'] == '') {
            $this->config['title'] = str_replace('_', ' ', ucfirst($this->name));
        }
        if (isset($params[$this->name])) {
            $this->value = $params[$this->name];
        } else {
            $this->value = $this->defaultValue();
        }
        //  $this->value=request()->get("parameters")+$this->name,@$this->config['value']);
    }

    public function defaultValue()
    {
        return isset($this->config['value']) && !empty($this->config['value']) ? $this->config['value'] : '';
    }

    public function scripts()
    {
        return [];
    }

      public function styles()
      {
          return [];
      }

    public function html()
    {
        return '';
    }

    public function queryValue()
    {
        return  $this->value;
    }

    public function render()
    {
        if ($this->hidden == '0') {
            return $this->html();
        } else {
            return "<input type='hidden' name='{$this->name}' value='{$this->value}'   />";
        }
    }
}
