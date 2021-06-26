<?php

/* @Insights/insightControls.twig */
class __TwigTemplate_05d99374de0d910c53c76f97eb0728eac90eaf6b22e5162e0201f214219b5550 extends Twig_Template
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
        echo "<div class=\"controls\">

    ";
        // line 3
        if (($this->getContext($context, "period") != "range")) {
            // line 4
            echo "
        ";
            // line 5
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Insights_ControlComparedToDescription")), "html", null, true);
            echo "

        ";
            // line 7
            if (($this->getContext($context, "period") == "day")) {
                // line 8
                echo "            <select size=\"1\" name=\"comparedToXPeriodsAgo\">
                <option value=\"1\" ";
                // line 9
                if (($this->getAttribute($this->getContext($context, "properties"), "compared_to_x_periods_ago", array()) == 1)) {
                    echo "selected";
                }
                echo ">
                   ";
                // line 10
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Insights_DayComparedToPreviousDay")), "html", null, true);
                echo "
                </option>
                <option value=\"7\" ";
                // line 12
                if (($this->getAttribute($this->getContext($context, "properties"), "compared_to_x_periods_ago", array()) == 7)) {
                    echo "selected";
                }
                echo ">
                    ";
                // line 13
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Insights_DayComparedToPreviousWeek")), "html", null, true);
                echo "
                </option>
                <option value=\"365\" ";
                // line 15
                if (($this->getAttribute($this->getContext($context, "properties"), "compared_to_x_periods_ago", array()) == 365)) {
                    echo "selected";
                }
                echo ">
                    ";
                // line 16
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Insights_DayComparedToPreviousYear")), "html", null, true);
                echo "
                </option>
            </select>
        ";
            } elseif ((            // line 19
$this->getContext($context, "period") == "month")) {
                // line 20
                echo "            <select size=\"1\" name=\"comparedToXPeriodsAgo\">
                <option value=\"1\" ";
                // line 21
                if (($this->getAttribute($this->getContext($context, "properties"), "compared_to_x_periods_ago", array()) == 1)) {
                    echo "selected";
                }
                echo ">
                    ";
                // line 22
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Insights_MonthComparedToPreviousMonth")), "html", null, true);
                echo "
                </option>
                <option value=\"12\" ";
                // line 24
                if (($this->getAttribute($this->getContext($context, "properties"), "compared_to_x_periods_ago", array()) == 12)) {
                    echo "selected";
                }
                echo ">
                    ";
                // line 25
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Insights_MonthComparedToPreviousYear")), "html", null, true);
                echo "
                </option>
            </select>
        ";
            } elseif ((            // line 28
$this->getContext($context, "period") == "week")) {
                // line 29
                echo "            ";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Insights_WeekComparedToPreviousWeek")), "html", null, true);
                echo "
        ";
            } elseif ((            // line 30
$this->getContext($context, "period") == "year")) {
                // line 31
                echo "            ";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Insights_YearComparedToPreviousYear")), "html", null, true);
                echo "
        ";
            }
            // line 33
            echo "    ";
        }
        // line 34
        echo "
    <hr class=\"controlSeparator\" />

    ";
        // line 37
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Insights_Filter")), "html", null, true);
        echo "

    <select size=\"1\" name=\"filterBy\" title=\"";
        // line 39
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Insights_ControlFilterByDescription")), "html_attr");
        echo "\">
        <option ";
        // line 40
        if ( !$this->getAttribute($this->getContext($context, "properties"), "filter_by", array())) {
            echo "selected";
        }
        echo " value=\"all\">
            ";
        // line 41
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_All")), "html", null, true);
        echo "
        </option>
        <option ";
        // line 43
        if (($this->getAttribute($this->getContext($context, "properties"), "filter_by", array()) == "movers")) {
            echo "selected";
        }
        echo " value=\"movers\">
            ";
        // line 44
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Insights_FilterOnlyMovers")), "html", null, true);
        echo "
        </option>
        <option ";
        // line 46
        if (($this->getAttribute($this->getContext($context, "properties"), "filter_by", array()) == "new")) {
            echo "selected";
        }
        echo " value=\"new\">
            ";
        // line 47
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Insights_FilterOnlyNew")), "html", null, true);
        echo "
        </option>
        <option ";
        // line 49
        if (($this->getAttribute($this->getContext($context, "properties"), "filter_by", array()) == "disappeared")) {
            echo "selected";
        }
        echo " value=\"disappeared\">
            ";
        // line 50
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Insights_FilterOnlyDisappeared")), "html", null, true);
        echo "
        </option>
    </select>

    <select size=\"1\" name=\"showIncreaseOrDecrease\" title=\"Show increaser and/or decreaser\">
        <option value=\"both\" ";
        // line 55
        if (($this->getAttribute($this->getContext($context, "properties"), "limit_increaser", array()) && $this->getAttribute($this->getContext($context, "properties"), "limit_decreaser", array()))) {
            echo "selected";
        }
        echo ">
            ";
        // line 56
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Insights_FilterIncreaserAndDecreaser")), "html", null, true);
        echo "
        </option>
        <option value=\"increase\" ";
        // line 58
        if (($this->getAttribute($this->getContext($context, "properties"), "limit_increaser", array()) &&  !$this->getAttribute($this->getContext($context, "properties"), "limit_decreaser", array()))) {
            echo "selected";
        }
        echo ">
            ";
        // line 59
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Insights_FilterOnlyIncreaser")), "html", null, true);
        echo "
        </option>
        <option value=\"decrease\" ";
        // line 61
        if (( !$this->getAttribute($this->getContext($context, "properties"), "limit_increaser", array()) && $this->getAttribute($this->getContext($context, "properties"), "limit_decreaser", array()))) {
            echo "selected";
        }
        echo ">
            ";
        // line 62
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Insights_FilterOnlyDecreaser")), "html", null, true);
        echo "
        </option>
    </select>
</div>";
    }

    public function getTemplateName()
    {
        return "@Insights/insightControls.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  207 => 62,  201 => 61,  196 => 59,  190 => 58,  185 => 56,  179 => 55,  171 => 50,  165 => 49,  160 => 47,  154 => 46,  149 => 44,  143 => 43,  138 => 41,  132 => 40,  128 => 39,  123 => 37,  118 => 34,  115 => 33,  109 => 31,  107 => 30,  102 => 29,  100 => 28,  94 => 25,  88 => 24,  83 => 22,  77 => 21,  74 => 20,  72 => 19,  66 => 16,  60 => 15,  55 => 13,  49 => 12,  44 => 10,  38 => 9,  35 => 8,  33 => 7,  28 => 5,  25 => 4,  23 => 3,  19 => 1,);
    }
}
