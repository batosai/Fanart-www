<?php
class Form_Decorator_Stars extends Zend_Form_Decorator_Abstract
{
    protected $_format = '<div class="control-group">
                            %s
                            <div id="stars">%s %s</div>
                          </div>';

    public function render($content)
    {
        $element = $this->getElement();
        $view    = $element->getView();
        $helper  = $element->helper;

        if (empty($content)) {
          $content = $element->getValue();
        }

        $attribs = $element->getAttribs();
        unset($attribs['helper']);

        $name    = htmlentities($element->getFullyQualifiedName());
        $label   = $element->getLabel();
        $label  .= $element->isRequired() ? '* :' : ' :';
        $description = $element->getDescription() ? '<p class="help-block">' . $element->getDescription() . '</p>' : '';

        $viewHelper = $view->$helper($name, $content, $attribs, $element->getMultiOptions(), '');

        $markup  = sprintf(
                    $this->_format,
                    $view->formLabel($element->getName(), $label, array('class' => 'starsLabel')),
                    $viewHelper,
                    $description
                  );

        return $markup;
    }
}