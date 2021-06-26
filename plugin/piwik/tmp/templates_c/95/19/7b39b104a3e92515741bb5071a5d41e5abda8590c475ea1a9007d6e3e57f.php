<?php

/* @Morpheus/layout.twig */
class __TwigTemplate_95197b39b104a3e92515741bb5071a5d41e5abda8590c475ea1a9007d6e3e57f extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'head' => array($this, 'block_head'),
            'pageTitle' => array($this, 'block_pageTitle'),
            'pageDescription' => array($this, 'block_pageDescription'),
            'body' => array($this, 'block_body'),
            'root' => array($this, 'block_root'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html id=\"ng-app\" ";
        // line 2
        if (array_key_exists("language", $context)) {
            echo "lang=\"";
            echo twig_escape_filter($this->env, $this->getContext($context, "language"), "html", null, true);
            echo "\"";
        }
        echo " ng-app=\"piwikApp\">
    <head>
        ";
        // line 4
        $this->displayBlock('head', $context, $blocks);
        // line 24
        echo "    </head>
    <!--[if lt IE 9 ]>
    <body id=\"";
        // line 26
        echo twig_escape_filter($this->env, ((array_key_exists("bodyId", $context)) ? (_twig_default_filter($this->getContext($context, "bodyId"), "")) : ("")), "html", null, true);
        echo "\" ng-app=\"app\" class=\"old-ie ";
        echo twig_escape_filter($this->env, ((array_key_exists("bodyClass", $context)) ? (_twig_default_filter($this->getContext($context, "bodyClass"), "")) : ("")), "html", null, true);
        echo "\">
    <![endif]-->
    <!--[if (gte IE 9)|!(IE)]><!-->
    <body id=\"";
        // line 29
        echo twig_escape_filter($this->env, ((array_key_exists("bodyId", $context)) ? (_twig_default_filter($this->getContext($context, "bodyId"), "")) : ("")), "html", null, true);
        echo "\" ng-app=\"app\" class=\"";
        echo twig_escape_filter($this->env, ((array_key_exists("bodyClass", $context)) ? (_twig_default_filter($this->getContext($context, "bodyClass"), "")) : ("")), "html", null, true);
        echo "\">
    <!--<![endif]-->

    ";
        // line 32
        $this->displayBlock('body', $context, $blocks);
        // line 43
        echo "
    </body>
</html>
";
    }

    // line 4
    public function block_head($context, array $blocks = array())
    {
        // line 5
        echo "            <meta charset=\"utf-8\">
            <title>";
        // line 6
        $this->displayBlock('pageTitle', $context, $blocks);
        echo "</title>
            <meta http-equiv=\"X-UA-Compatible\" content=\"IE=EDGE,chrome=1\"/>
            <meta name=\"viewport\" content=\"initial-scale=1.0\"/>
            <meta name=\"generator\" content=\"Piwik - free/libre analytics platform\"/>
            <meta name=\"description\" content=\"";
        // line 10
        $this->displayBlock('pageDescription', $context, $blocks);
        echo "\"/>
            <meta name=\"apple-itunes-app\" content=\"app-id=737216887\" />
            <meta name=\"google-play-app\" content=\"app-id=org.piwik.mobile2\">
            <link rel=\"shortcut icon\" href=\"";
        // line 13
        echo twig_escape_filter($this->env, ((array_key_exists("customFavicon", $context)) ? (_twig_default_filter($this->getContext($context, "customFavicon"), "plugins/CoreHome/images/favicon.ico")) : ("plugins/CoreHome/images/favicon.ico")), "html", null, true);
        echo "\"/>

            ";
        // line 15
        $this->env->loadTemplate("@CoreHome/_favicon.twig")->display($context);
        // line 16
        echo "            ";
        $this->env->loadTemplate("_jsGlobalVariables.twig")->display($context);
        // line 17
        echo "            ";
        $this->env->loadTemplate("_piwikTag.twig")->display($context);
        // line 18
        echo "            ";
        $this->env->loadTemplate("_jsCssIncludes.twig")->display($context);
        // line 19
        echo "
            <!--[if IE]>
            <link rel=\"stylesheet\" type=\"text/css\" href=\"plugins/Morpheus/stylesheets/ieonly.css\"/>
            <![endif]-->
        ";
    }

    // line 6
    public function block_pageTitle($context, array $blocks = array())
    {
        echo "Piwik";
    }

    // line 10
    public function block_pageDescription($context, array $blocks = array())
    {
    }

    // line 32
    public function block_body($context, array $blocks = array())
    {
        // line 33
        echo "
        ";
        // line 34
        $this->env->loadTemplate("_iframeBuster.twig")->display($context);
        // line 35
        echo "        ";
        $this->env->loadTemplate("@CoreHome/_javaScriptDisabled.twig")->display($context);
        // line 36
        echo "
        <div id=\"root\">
            ";
        // line 38
        $this->displayBlock('root', $context, $blocks);
        // line 40
        echo "        </div>

    ";
    }

    // line 38
    public function block_root($context, array $blocks = array())
    {
        // line 39
        echo "            ";
    }

    public function getTemplateName()
    {
        return "@Morpheus/layout.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  147 => 39,  144 => 38,  138 => 40,  136 => 38,  132 => 36,  129 => 35,  127 => 34,  124 => 33,  121 => 32,  116 => 10,  110 => 6,  102 => 19,  99 => 18,  96 => 17,  93 => 16,  91 => 15,  86 => 13,  80 => 10,  73 => 6,  70 => 5,  67 => 4,  60 => 43,  58 => 32,  50 => 29,  42 => 26,  38 => 24,  36 => 4,  27 => 2,  24 => 1,);
    }
}
