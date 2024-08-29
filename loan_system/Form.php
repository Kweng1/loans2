<?php
class Form {
    private $action;
    private $method;
    private $elements = [];

    public function __construct($action, $method = "post") {
        $this->action = $action;
        $this->method = $method;
    }

    public function addElement($element) {
        $this->elements[] = $element;
    }

    public function render() {
        echo '<form action="'.$this->action.'" method="'.$this->method.'" novalidate>';
        foreach ($this->elements as $element) {
            echo $element->render();
        }
        echo '</form>';
    }
}

class Input {
    private $type;
    private $name;
    private $placeholder;
    private $iconClass;
    private $required;

    public function __construct($type, $name, $placeholder, $iconClass, $required = true) {
        $this->type = $type;
        $this->name = $name;
        $this->placeholder = $placeholder;
        $this->iconClass = $iconClass;
        $this->required = $required;
    }

    public function render() {
        $requiredAttr = $this->required ? 'required' : '';
        return '<div class="input_box">
                  <input type="'.$this->type.'" name="'.$this->name.'" placeholder="'.$this->placeholder.'" '.$requiredAttr.' />
                  <i class="'.$this->iconClass.'"></i>
                </div>';
    }
}

class Button {
    private $text;

    public function __construct($text) {
        $this->text = $text;
    }

    public function render() {
        return '<button class="button">'.$this->text.'</button>';
    }
}
?>
