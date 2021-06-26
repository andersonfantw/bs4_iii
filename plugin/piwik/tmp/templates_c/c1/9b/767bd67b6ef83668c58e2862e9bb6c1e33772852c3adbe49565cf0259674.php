<?php

/* @Dashboard/_header.twig */
class __TwigTemplate_c19b767bd67b6ef83668c58e2862e9bb6c1e33772852c3adbe49565cf0259674 extends Twig_Template
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
        // line 2
        echo "<!DOCTYPE html>
<html>
<head>
    <meta charset=\"utf-8\">
\t<meta http-equiv=\"x-ua-compatible\" content=\"IE=EDGE,chrome=1\" >
    <title>";
        // line 7
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Dashboard_Dashboard")), "html", null, true);
        echo " - ";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreHome_WebAnalyticsReports")), "html", null, true);
        echo "</title>

    <!--[if lt IE 9]>
        <script language=\"javascript\" type=\"text/javascript\" src=\"libs/jqplot/excanvas.min.js\"></script>
    <![endif]-->

    <link rel=\"stylesheet\" type=\"text/css\" href=\"plugins/Dashboard/stylesheets/standalone.css\" />
";
        // line 14
        $this->env->loadTemplate("_jsGlobalVariables.twig")->display($context);
        // line 15
        $this->env->loadTemplate("_jsCssIncludes.twig")->display($context);
        // line 16
        echo "</head>
<body id=\"standalone\">
";
    }

    public function getTemplateName()
    {
        return "@Dashboard/_header.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  42 => 16,  40 => 15,  38 => 14,  26 => 7,  19 => 2,);
    }
}
