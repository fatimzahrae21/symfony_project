<?php


namespace App\Form;

use App\Entity\Recipe;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\String\Slugger\AsciiSlugger;

class FormListenerFactory {

    public function autoSlug(string $field): callable {

        return function (PreSubmitEvent $event) use ($field){

            $data = $event->getData();
            if (empty($data['slug'])){
                $slugger = new AsciiSlugger();
                $data['slug'] = strtolower($slugger->slug($data[$field]));
                $event->setData($data);

            }
        };
    }

    public function timestamps(): callable
    {
        return function (PreSubmitEvent $event) {
            $data = $event->getData(); // Les données sont un tableau ici
    
            if (!is_array($data)) {
                return;
            }
    
            $data['updated_at'] = (new \DateTimeImmutable())->format('Y-m-d H:i:s');
    
            if (empty($data['id'])) {
                $data['created_at'] = (new \DateTimeImmutable())->format('Y-m-d H:i:s');
            }
    
            $event->setData($data);
        };
    }
}