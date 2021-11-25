<?php

namespace SymfonySkillbox\HomeworkBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use SymfonySkillbox\HomeworkBundle\DependencyInjection\SymfonySkillboxHomeworkBundleExtension;

class SymfonySkillboxHomeworkBundle extends Bundle
{
    public function getContainerExtension()
    {
       if (!$this->extension) {
           $this->extension = new SymfonySkillboxHomeworkBundleExtension();
       }
       
       return $this->extension;
    }
}