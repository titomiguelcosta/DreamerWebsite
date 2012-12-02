<?php
namespace Aviario;
class Galinha
{
    /**
     * Returns array with post entry details
     * 
     * @param string $slug
     * @return array
     */
    public function getPost($slug)
    {
        return array('nome' => 'Joana'.rand(0, 1010101010));
    }
}
