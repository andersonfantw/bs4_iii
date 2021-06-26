<?php

/* @CoreHome/getDefaultIndexView.twig */
class __TwigTemplate_7cd6cde454ca9ad6d5c61dd555ad4fbeb25c077a9f5fc04ec120e61c8099fb52 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        try {
            $this->parent = $this->env->loadTemplate("dashboard.twig");
        } catch (Twig_Error_Loader $e) {
            $e->setTemplateFile($this->getTemplateName());
            $e->setTemplateLine(1);

            throw $e;
        }

        $this->blocks = array(
            'notification' => array($this, 'block_notification'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "dashboard.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_notification($context, array $blocks = array())
    {
    }

    // line 3
    public function block_content($context, array $blocks = array())
    {
        // line 4
        echo "
";
        // line 5
        $this->env->loadTemplate("@CoreHome/_siteSelectHeader.twig")->display($context);
        // line 6
        echo "
";
        // line 7
        if ((array_key_exists("menu", $context) && $this->getContext($context, "menu"))) {
            // line 8
            echo "  ";
            $this->env->loadTemplate("@CoreHome/_menu.twig")->display($context);
        }
        // line 10
        echo "
";
        // line 11
        $this->env->loadTemplate("@CoreHome/_indexContent.twig")->display($context);
        // line 12
        echo "
";
    }

    public function getTemplateName()
    {
        return "@CoreHome/getDefaultIndexView.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  64 => 12,  62 => 11,  59 => 10,  55 => 8,  53 => 7,  50 => 6,  48 => 5,  45 => 4,  42 => 3,  37 => 2,  11 => 1,);
    }
}
