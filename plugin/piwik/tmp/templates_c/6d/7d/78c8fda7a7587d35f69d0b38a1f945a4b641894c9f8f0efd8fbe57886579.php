<?php

/* @CoreHome/_dataTableJS.twig */
class __TwigTemplate_6d7d78c8fda7a7587d35f69d0b38a1f945a4b641894c9f8f0efd8fbe57886579 extends Twig_Template
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
        echo "<script type=\"text/javascript\" defer=\"defer\">
    \$(document).ready(function () {
        require('piwik/UI/DataTable').initNewDataTables();
    });
</script>
";
    }

    public function getTemplateName()
    {
        return "@CoreHome/_dataTableJS.twig";
    }

    public function getDebugInfo()
    {
        return array (  19 => 1,);
    }
}
