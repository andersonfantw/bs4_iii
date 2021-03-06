<?php

/* @CoreHome/_dataTable.twig */
class __TwigTemplate_45734a81085b8fc631cc0050ce98eccecb33371b22c6cb57c20908407d64b220 extends Twig_Template
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
        if ($this->getAttribute($this->getContext($context, "properties"), "show_visualization_only", array())) {
            // line 2
            echo "    ";
            $this->env->resolveTemplate($this->getContext($context, "visualizationTemplate"))->display($context);
        } else {
            // line 5
            $context["summaryRowId"] = twig_constant("Piwik\\DataTable::ID_SUMMARY_ROW");
            // line 6
            $context["isSubtable"] = ($this->getAttribute($this->getContext($context, "javascriptVariablesToSet", true), "idSubtable", array(), "any", true, true) && ($this->getAttribute($this->getContext($context, "javascriptVariablesToSet"), "idSubtable", array()) != 0));
            // line 7
            echo "<div class=\"dataTable ";
            echo twig_escape_filter($this->env, $this->getContext($context, "visualizationCssClass"), "html", null, true);
            echo " ";
            echo twig_escape_filter($this->env, (($this->getAttribute($this->getContext($context, "properties", true), "datatable_css_class", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute($this->getContext($context, "properties", true), "datatable_css_class", array()), "")) : ("")), "html", null, true);
            echo " ";
            if ($this->getContext($context, "isSubtable")) {
                echo "subDataTable";
            }
            echo "\"
     data-table-type=\"";
            // line 8
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "properties"), "datatable_js_type", array()), "html", null, true);
            echo "\"
     data-report=\"";
            // line 9
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "properties"), "report_id", array()), "html", null, true);
            echo "\"
     data-report-metadata=\"";
            // line 10
            echo twig_escape_filter($this->env, twig_jsonencode_filter($this->getContext($context, "reportMetdadata")), "html_attr");
            echo "\"
     data-props=\"";
            // line 11
            if (twig_test_empty($this->getContext($context, "clientSideProperties"))) {
                echo "{}";
            } else {
                echo twig_escape_filter($this->env, twig_jsonencode_filter($this->getContext($context, "clientSideProperties")), "html", null, true);
            }
            echo "\"
     data-params=\"";
            // line 12
            if (twig_test_empty($this->getContext($context, "clientSideParameters"))) {
                echo "{}";
            } else {
                echo twig_escape_filter($this->env, twig_jsonencode_filter($this->getContext($context, "clientSideParameters")), "html", null, true);
            }
            echo "\">
    <div class=\"reportDocumentation\">
        ";
            // line 14
            if ( !twig_test_empty((($this->getAttribute($this->getContext($context, "properties", true), "documentation", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute($this->getContext($context, "properties", true), "documentation", array()))) : ("")))) {
                echo "<p>";
                echo $this->getAttribute($this->getContext($context, "properties"), "documentation", array());
                echo "</p>";
            }
            // line 15
            echo "        ";
            if (array_key_exists("reportLastUpdatedMessage", $context)) {
                echo "<span class='helpDate'>";
                echo twig_escape_filter($this->env, $this->getContext($context, "reportLastUpdatedMessage"), "html", null, true);
                echo "</span>";
            }
            // line 16
            echo "    </div>
    <div class=\"dataTableWrapper\">
        ";
            // line 18
            if (array_key_exists("error", $context)) {
                // line 19
                echo "            ";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "error"), "message", array()), "html", null, true);
                echo "
        ";
            } else {
                // line 21
                echo "            ";
                if ((twig_test_empty($this->getContext($context, "dataTable")) || ((array_key_exists("dataTableHasNoData", $context)) ? (_twig_default_filter($this->getContext($context, "dataTableHasNoData"), false)) : (false)))) {
                    // line 22
                    echo "                <div class=\"pk-emptyDataTable\">
                ";
                    // line 23
                    if ((array_key_exists("showReportDataWasPurgedMessage", $context) && $this->getContext($context, "showReportDataWasPurgedMessage"))) {
                        // line 24
                        echo "                    ";
                        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreHome_DataForThisReportHasBeenPurged", $this->getContext($context, "deleteReportsOlderThan"))), "html", null, true);
                        echo "
                ";
                    } else {
                        // line 26
                        echo "                    ";
                        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreHome_ThereIsNoDataForThisReport")), "html", null, true);
                        echo "
                ";
                    }
                    // line 28
                    echo "                </div>
            ";
                } else {
                    // line 30
                    echo "                ";
                    $this->env->resolveTemplate($this->getContext($context, "visualizationTemplate"))->display($context);
                    // line 31
                    echo "            ";
                }
                // line 32
                echo "
            ";
                // line 33
                if ($this->getAttribute($this->getContext($context, "properties"), "show_footer", array())) {
                    // line 34
                    echo "                ";
                    $this->env->loadTemplate("@CoreHome/_dataTableFooter.twig")->display($context);
                    // line 35
                    echo "            ";
                }
                // line 36
                echo "            ";
                $this->env->loadTemplate("@CoreHome/_dataTableJS.twig")->display($context);
                // line 37
                echo "        ";
            }
            // line 38
            echo "    </div>
</div>";
        }
    }

    public function getTemplateName()
    {
        return "@CoreHome/_dataTable.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  141 => 38,  138 => 37,  135 => 36,  132 => 35,  129 => 34,  127 => 33,  124 => 32,  121 => 31,  118 => 30,  114 => 28,  108 => 26,  102 => 24,  100 => 23,  97 => 22,  94 => 21,  88 => 19,  86 => 18,  82 => 16,  75 => 15,  69 => 14,  60 => 12,  52 => 11,  48 => 10,  44 => 9,  40 => 8,  29 => 7,  27 => 6,  25 => 5,  21 => 2,  19 => 1,);
    }
}
