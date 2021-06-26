<?php

/* @CoreHome/getDonateForm.twig */
class __TwigTemplate_fba7f9c16e21a1fdb6388bedad13ff4e27d2e743b230a11cc927e63d013f0ab4 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        $this->env->loadTemplate("@CoreHome/_donate.twig")->display($context);
    }

    public function getTemplateName()
    {
        return "@CoreHome/getDonateForm.twig";
    }

    public function getDebugInfo()
    {
        return array (  19 => 1,);
    }
}
