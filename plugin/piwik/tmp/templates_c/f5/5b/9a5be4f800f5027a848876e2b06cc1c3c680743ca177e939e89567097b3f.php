<?php

/* @UserCountryMap/visitorMap.twig */
class __TwigTemplate_f55b9a5be4f800f5027a848876e2b06cc1c3c680743ca177e939e89567097b3f extends Twig_Template
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
        echo "<section>
<div class=\"UserCountryMap\" style=\"position:relative; overflow:hidden;\">
    <div class=\"UserCountryMap_container\">
        <div class=\"UserCountryMap_map\" style=\"overflow:hidden;\"></div>
        <div class=\"UserCountryMap-overlay UserCountryMap-title\">
            <div class=\"content\">
                <!--<div class=\"map-title\" style=\"font-weight:bold; color:#9A9386;\"></div>-->
                <div class=\"map-stats\" style=\"color:#565656;\"></div>
            </div>
        </div>
        <div class=\"UserCountryMap-overlay UserCountryMap-legend\">
            <div class=\"content\">
            </div>
        </div>
        <div class=\"UserCountryMap-tooltip UserCountryMap-info\">
            <div class=\"content unlocated-stats\" data-tpl=\"";
        // line 16
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountryMap_Unlocated")), "html", null, true);
        echo "\">
            </div>
        </div>
        <div class=\"UserCountryMap-info-btn\" data-tooltip-target=\".UserCountryMap-tooltip\"></div>
    </div>
    <div class=\"mapWidgetStatus\">
        ";
        // line 22
        if ($this->getContext($context, "noData")) {
            // line 23
            echo "            <div class=\"pk-emptyDataTable\">";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreHome_ThereIsNoDataForThisReport")), "html", null, true);
            echo "</div>
        ";
        } else {
            // line 25
            echo "            <span class=\"loadingPiwik\">
                <img src=\"plugins/Morpheus/images/loading-blue.gif\" />
                ";
            // line 27
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_LoadingData")), "html", null, true);
            echo "...
            </span>
        ";
        }
        // line 30
        echo "    </div>
    <div class=\"dataTableFeatures\" style=\"padding-top:0;\">
        <div class=\"dataTableFooterIcons\">
            <div class=\"dataTableFooterWrap\" var=\"graphVerticalBar\">
                <img class=\"UserCountryMap-activeItem dataTableFooterActiveItem\" src=\"plugins/Morpheus/images/data_table_footer_active_item.png\" style=\"left: 25px;\" />

                <div class=\"tableIconsGroup\">
                    <span class=\"tableAllColumnsSwitch\">
                        <a class=\"UserCountryMap-btn-zoom tableIcon\" format=\"table\">
                            <img src=\"plugins/Morpheus/images/zoom-out.png\" title=\"Zoom to world\" />
                        </a>
                    </span>
                </div>
                <div class=\"tableIconsGroup UserCountryMap-view-mode-buttons\">
                    <span class=\"tableAllColumnsSwitch\">
                        <a var=\"tableAllColumns\" class=\"UserCountryMap-btn-region tableIcon activeIcon\" format=\"tableAllColumns\"
                           data-region=\"";
        // line 46
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountryMap_Regions")), "html", null, true);
        echo "\" data-country=\"";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountryMap_Countries")), "html", null, true);
        echo "\">
                            <img src=\"plugins/UserCountryMap/images/regions.png\" title=\"Show visitors per region/country\" />
                            <span style=\"margin:0;\">";
        // line 48
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountryMap_Countries")), "html", null, true);
        echo "</span>&nbsp;
                        </a>
                        <a var=\"tableGoals\" class=\"UserCountryMap-btn-city tableIcon inactiveIco\" format=\"tableGoals\">
                            <img src=\"plugins/UserCountryMap/images/cities.png\" title=\"Show visitors per city\" />
                            <span style=\"margin:0;\">";
        // line 52
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountryMap_Cities")), "html", null, true);
        echo "</span>&nbsp;
                        </a>
                    </span>
                </div>
            </div>

            <select class=\"userCountryMapSelectMetrics\" style=\"float:right;margin-right:0;margin-bottom:5px;max-width: 10em;font-size:10px;\">
                ";
        // line 59
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getContext($context, "metrics"));
        foreach ($context['_seq'] as $context["_key"] => $context["metric"]) {
            // line 60
            echo "                    <option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($context["metric"], 0, array(), "array"), "html", null, true);
            echo "\" ";
            if (($this->getAttribute($context["metric"], 0, array(), "array") == $this->getContext($context, "defaultMetric"))) {
                echo "selected=\"selected\"";
            }
            echo "}>";
            echo twig_escape_filter($this->env, $this->getAttribute($context["metric"], 1, array(), "array"), "html", null, true);
            echo "</option>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['metric'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 62
        echo "            </select>
            <select class=\"userCountryMapSelectCountry\" style=\"float:right;margin-right:5px;margin-bottom:5px; max-width: 10em;font-size:10px;\">
                <option value=\"world\">";
        // line 64
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountryMap_WorldWide")), "html", null, true);
        echo "</option>
                <option disabled=\"disabled\">––––––</option>
                <option value=\"AF\">";
        // line 66
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountry_continent_afr")), "html", null, true);
        echo "</option>
                <option value=\"AS\">";
        // line 67
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountry_continent_asi")), "html", null, true);
        echo "</option>
                <option value=\"EU\">";
        // line 68
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountry_continent_eur")), "html", null, true);
        echo "</option>
                <option value=\"NA\">";
        // line 69
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountry_continent_amn")), "html", null, true);
        echo "</option>
                <option value=\"OC\">";
        // line 70
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountry_continent_oce")), "html", null, true);
        echo "</option>
                <option value=\"SA\">";
        // line 71
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountry_continent_ams")), "html", null, true);
        echo "</option>
                <option disabled=\"disabled\">––––––</option>
            </select>
        </div>
    </div>
</div>
</section>

";
        // line 79
        if ( !$this->getContext($context, "noData")) {
            // line 80
            echo "<!-- configure some piwik vars -->
<script type=\"text/javascript\">
    var visitorMap,
    config = JSON.parse('";
            // line 83
            echo twig_escape_filter($this->env, twig_escape_filter($this->env, $this->getContext($context, "config"), "js"), "html", null, true);
            echo "');
    config._ = JSON.parse('";
            // line 84
            echo twig_escape_filter($this->env, twig_escape_filter($this->env, $this->getContext($context, "localeJSON"), "js"), "html", null, true);
            echo "');
    config.reqParams = JSON.parse('";
            // line 85
            echo twig_escape_filter($this->env, twig_escape_filter($this->env, $this->getContext($context, "reqParamsJSON"), "js"), "html", null, true);
            echo "');

    \$('.UserCountryMap').addClass('dataTable');

    if (\$('#dashboardWidgetsArea').length) {
        // dashboard mode
        var \$widgetContent = \$('.UserCountryMap').parents('.widgetContent');

        \$widgetContent.on('widget:create',function (evt, widget) {
            visitorMap = new UserCountryMap.VisitorMap(config, widget);
        }).on('widget:maximise',function (evt) {
                    visitorMap.resize();
                }).on('widget:minimise',function (evt) {
                    visitorMap.resize();
                }).on('widget:destroy', function (evt) {
                    visitorMap.destroy();
                });
    } else {
        // stand-alone mode
        visitorMap = new UserCountryMap.VisitorMap(config);
    }

</script>
";
        }
    }

    public function getTemplateName()
    {
        return "@UserCountryMap/visitorMap.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  179 => 85,  175 => 84,  171 => 83,  166 => 80,  164 => 79,  153 => 71,  149 => 70,  145 => 69,  141 => 68,  137 => 67,  133 => 66,  128 => 64,  124 => 62,  109 => 60,  105 => 59,  95 => 52,  88 => 48,  81 => 46,  63 => 30,  57 => 27,  53 => 25,  47 => 23,  45 => 22,  36 => 16,  19 => 1,);
    }
}
