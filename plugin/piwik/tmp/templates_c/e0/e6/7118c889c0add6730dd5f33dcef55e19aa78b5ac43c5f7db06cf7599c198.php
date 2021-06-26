<?php

/* @Widgetize/iframe.twig */
class __TwigTemplate_e0e67118c889c0add6730dd5f33dcef55e19aa78b5ac43c5f7db06cf7599c198 extends Twig_Template
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
        echo "<!DOCTYPE html>
<html id=\"ng-app\" ng-app=\"piwikApp\">
    <head>
        <meta charset=\"utf-8\">
        ";
        // line 5
        $this->env->loadTemplate("_jsGlobalVariables.twig")->display($context);
        // line 6
        echo "        <!--[if lt IE 9]>
        <script language=\"javascript\" type=\"text/javascript\" src=\"libs/jqplot/excanvas.min.js\"></script>
        <![endif]-->
        ";
        // line 9
        $this->env->loadTemplate("_jsCssIncludes.twig")->display($context);
        // line 10
        echo "        <!--[if IE]>
        <link rel=\"stylesheet\" type=\"text/css\" href=\"plugins/Morpheus/stylesheets/ieonly.css\"/>
        <![endif]-->
    </head>
    <!--[if lt IE 9 ]>
    <body ng-app=\"app\" class=\"old-ie\"> <![endif]-->
    <!--[if (gte IE 9)|!(IE)]><!-->
    <body ng-app=\"app\">
    <![endif]-->
        <div class=\"widget\">
            ";
        // line 20
        echo $this->getContext($context, "content");
        echo "
        </div>
    </body>
</html>
";
    }

    public function getTemplateName()
    {
        return "@Widgetize/iframe.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  46 => 20,  34 => 10,  32 => 9,  27 => 6,  25 => 5,  19 => 1,);
    }
}
