<?php

namespace Item\View\Helper;

use Zend\View\Helper\AbstractHelper;

class ShowMessages extends AbstractHelper
{

    public function __invoke()
    {
        $error_messages = $this->view->flashMessenger()->getErrorMessages();
        $messages = $this->view->flashMessenger()->getMessages();

        $result = '';
        if (count($error_messages)) {
            $result .= '<div class="alert alert-warning">';
            foreach ($error_messages as $message) {
                $result .= $message . ' ';
            }
            $result .= '</div>';
        }
        if (count($messages)) {
            $result .= '<div class="alert alert-success">';
            foreach ($messages as $message) {
                $result .= $message . ' ';
            }
            $result .= '</div>';
        }
        return $result;
    }
}
