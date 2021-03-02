<?php

namespace A1\Channel;

class A1Message
{
    public $content;

    public function content($content)
    {
      $this->content = $content;

      return $this;
    }
}
