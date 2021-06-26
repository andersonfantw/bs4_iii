<?php

/* @Live/_dataTableViz_visitorLog.twig */
class __TwigTemplate_7387621772ebc1b2aebd3437ad81623534a4b3d0663b409e0294198b45407042 extends Twig_Template
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
        $context["displayVisitorsInOwnColumn"] = (($this->getContext($context, "isWidget")) ? (false) : (true));
        // line 2
        $context["displayReferrersInOwnColumn"] = (($this->getAttribute($this->getContext($context, "clientSideParameters"), "smallWidth", array())) ? (false) : (true));
        // line 3
        echo "<table class=\"dataTable\" cellspacing=\"0\" width=\"100%\" style=\"width:100%;table-layout:fixed;\">
<thead>
<tr>
    <th style=\"display:none;\"></th>
    <th id=\"label\" class=\"sortable label\" style=\"cursor: auto; width: 20%; \">
        <div id=\"thDIV\">";
        // line 8
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Date")), "html", null, true);
        echo "</div>
    </th>
    ";
        // line 10
        if ($this->getContext($context, "displayVisitorsInOwnColumn")) {
            // line 11
            echo "        <th id=\"label\" class=\"sortable label\" style=\"cursor: auto;width: 15%;\">
            <div id=\"thDIV\">";
            // line 12
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Visitors")), "html", null, true);
            echo "</div>
        </th>
    ";
        }
        // line 15
        echo "    ";
        if ($this->getContext($context, "displayReferrersInOwnColumn")) {
            // line 16
            echo "    <th id=\"label\" class=\"sortable label\" style=\"cursor: auto;width: 15%\">
        <div id=\"thDIV\">";
            // line 17
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Live_Referrer_URL")), "html", null, true);
            echo "</div>
    </th>
    ";
        }
        // line 20
        echo "    <th id=\"label\" class=\"sortable label\" style=\"cursor: auto;width: 60%\">
        <div id=\"thDIV\">";
        // line 21
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_ColumnNbActions")), "html", null, true);
        echo "</div>
    </th>
</tr>
</thead>
<tbody>
";
        // line 26
        $context["cycleIndex"] = 0;
        // line 27
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getContext($context, "dataTable"), "getRows", array(), "method"));
        $context['loop'] = array(
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        );
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["visitor"]) {
            // line 28
            echo "    ";
            $context["visitHasEcommerceActivity"] = $this->getAttribute($context["visitor"], "getColumn", array(0 => "visitEcommerceStatusIcon"), "method");
            // line 29
            echo "    ";
            $context["breakBeforeVisitorRank"] = ((($this->getContext($context, "visitHasEcommerceActivity") && $this->getAttribute($context["visitor"], "getColumn", array(0 => "visitorTypeIcon"), "method"))) ? (true) : (false));
            // line 30
            echo "    ";
            ob_start();
            // line 31
            echo "
    <span class=\"visitorLogIcons\">

        <span class=\"visitorType\">
        ";
            // line 35
            if ($this->getAttribute($context["visitor"], "getColumn", array(0 => "visitorTypeIcon"), "method")) {
                // line 36
                echo "            <span>
                <img src=\"";
                // line 37
                echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "visitorTypeIcon"), "method"), "html", null, true);
                echo "\"
                     title=\"";
                // line 38
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_ReturningVisitor")), "html", null, true);
                echo " - ";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_NVisits", $this->getAttribute($context["visitor"], "getColumn", array(0 => "visitCount"), "method"))), "html", null, true);
                echo "\"/>
            </span>
        ";
            }
            // line 41
            echo "
        ";
            // line 43
            echo "        ";
            if ($this->getAttribute($context["visitor"], "getColumn", array(0 => "visitConverted"), "method")) {
                // line 44
                echo "            <span title=\"";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_VisitConvertedNGoals", $this->getAttribute($context["visitor"], "getColumn", array(0 => "goalConversions"), "method"))), "html", null, true);
                echo "\" class='visitorRank'
                  ";
                // line 45
                if (( !$this->getContext($context, "displayVisitorsInOwnColumn") || $this->getContext($context, "breakBeforeVisitorRank"))) {
                    echo "style=\"margin-left:0;\"";
                }
                echo ">
                <img src=\"";
                // line 46
                echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "visitConvertedIcon"), "method"), "html", null, true);
                echo "\"/>
                <span class='hash'>#</span>
                ";
                // line 48
                echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "goalConversions"), "method"), "html", null, true);
                echo "
                ";
                // line 49
                if ($this->getContext($context, "visitHasEcommerceActivity")) {
                    // line 50
                    echo "                    &nbsp;-
                    <img src=\"";
                    // line 51
                    echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "visitEcommerceStatusIcon"), "method"), "html", null, true);
                    echo "\" title=\"";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "visitEcommerceStatus"), "method"), "html", null, true);
                    echo "\"/>
                ";
                }
                // line 53
                echo "            </span>
        ";
                // line 55
                echo "        ";
            } elseif ($this->getContext($context, "visitHasEcommerceActivity")) {
                // line 56
                echo "            <span><img src=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "visitEcommerceStatusIcon"), "method"), "html", null, true);
                echo "\" title=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "visitEcommerceStatus"), "method"), "html", null, true);
                echo "\"/></span>
        ";
            }
            // line 58
            echo "        </span>

        <span class=\"visitorDetails\">
        ";
            // line 61
            if ($this->getAttribute($context["visitor"], "getColumn", array(0 => "countryFlag"), "method")) {
                // line 62
                echo "            <span class=\"visitorLogIconWithDetails\">
                <img src=\"";
                // line 63
                echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "countryFlag"), "method"), "html", null, true);
                echo "\"/>
                <ul class=\"details\">
                    <li>";
                // line 65
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountry_Country")), "html", null, true);
                echo ": ";
                echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "country"), "method"), "html", null, true);
                echo "</li>
                    ";
                // line 66
                if ($this->getAttribute($context["visitor"], "getColumn", array(0 => "region"), "method")) {
                    echo "<li>";
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountry_Region")), "html", null, true);
                    echo ": ";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "region"), "method"), "html", null, true);
                    echo "</li>";
                }
                // line 67
                echo "                    ";
                if ($this->getAttribute($context["visitor"], "getColumn", array(0 => "city"), "method")) {
                    echo "<li>";
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountry_City")), "html", null, true);
                    echo ": ";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "city"), "method"), "html", null, true);
                    echo "</li>";
                }
                // line 68
                echo "                </ul>
            </span>
        ";
            }
            // line 71
            echo "        ";
            if ($this->getAttribute($context["visitor"], "getColumn", array(0 => "browserIcon"), "method")) {
                // line 72
                echo "            <span class=\"visitorLogIconWithDetails\">
                <img src=\"";
                // line 73
                echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "browserIcon"), "method"), "html", null, true);
                echo "\"/>
                <ul class=\"details\">
                    <li>";
                // line 75
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("DevicesDetection_ColumnBrowser")), "html", null, true);
                echo ": ";
                echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "browser"), "method"), "html", null, true);
                echo "</li>
                    <li>";
                // line 76
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("DevicesDetection_BrowserEngine")), "html", null, true);
                echo ": ";
                echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "browserFamily"), "method"), "html", null, true);
                echo "</li>
                    ";
                // line 77
                if ((twig_length_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "pluginsIcons"), "method")) > 0)) {
                    echo "<li>";
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Plugins")), "html", null, true);
                    echo ":
                        ";
                    // line 78
                    $context['_parent'] = (array) $context;
                    $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["visitor"], "getColumn", array(0 => "pluginsIcons"), "method"));
                    foreach ($context['_seq'] as $context["_key"] => $context["pluginIcon"]) {
                        // line 79
                        echo "                            <img src=\"";
                        echo twig_escape_filter($this->env, $this->getAttribute($context["pluginIcon"], "pluginIcon", array()), "html", null, true);
                        echo "\" title=\"";
                        echo twig_escape_filter($this->env, twig_capitalize_string_filter($this->env, $this->getAttribute($context["pluginIcon"], "pluginName", array()), true), "html", null, true);
                        echo "\" alt=\"";
                        echo twig_escape_filter($this->env, twig_capitalize_string_filter($this->env, $this->getAttribute($context["pluginIcon"], "pluginName", array()), true), "html", null, true);
                        echo "\"/>
                        ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['pluginIcon'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 81
                    echo "                    </li>";
                }
                // line 82
                echo "                </ul>
            </span>
        ";
            }
            // line 85
            echo "        ";
            if ($this->getAttribute($context["visitor"], "getColumn", array(0 => "operatingSystemIcon"), "method")) {
                // line 86
                echo "            <span class=\"visitorLogIconWithDetails\">
                <img src=\"";
                // line 87
                echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "operatingSystemIcon"), "method"), "html", null, true);
                echo "\"/>
                <ul class=\"details\">
                    <li>";
                // line 89
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("DevicesDetection_ColumnOperatingSystem")), "html", null, true);
                echo ": ";
                echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "operatingSystem"), "method"), "html", null, true);
                echo "</li>
                </ul>
            </span>
        ";
            }
            // line 93
            echo "        ";
            if ($this->getAttribute($context["visitor"], "getColumn", array(0 => "deviceTypeIcon"), "method")) {
                // line 94
                echo "            <span class=\"visitorLogIconWithDetails\">
                <img src=\"";
                // line 95
                echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "deviceTypeIcon"), "method"), "html", null, true);
                echo "\"/>
                <ul class=\"details\">
                    <li>";
                // line 97
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("DevicesDetection_DeviceType")), "html", null, true);
                echo ": ";
                echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "deviceType"), "method"), "html", null, true);
                echo "</li>
                    ";
                // line 98
                if ($this->getAttribute($context["visitor"], "getColumn", array(0 => "deviceBrand"), "method")) {
                    echo "<li>";
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("DevicesDetection_DeviceBrand")), "html", null, true);
                    echo ": ";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "deviceBrand"), "method"), "html", null, true);
                    echo "</li>";
                }
                // line 99
                echo "                    ";
                if ($this->getAttribute($context["visitor"], "getColumn", array(0 => "deviceModel"), "method")) {
                    echo "<li>";
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("DevicesDetection_DeviceModel")), "html", null, true);
                    echo ": ";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "deviceModel"), "method"), "html", null, true);
                    echo "</li>";
                }
                // line 100
                echo "                    ";
                if ($this->getAttribute($context["visitor"], "getColumn", array(0 => "resolution"), "method")) {
                    echo "<li>";
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Resolution_ColumnResolution")), "html", null, true);
                    echo ": ";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "resolution"), "method"), "html", null, true);
                    echo "</li>";
                }
                // line 101
                echo "                </ul>
            </span>
        ";
            }
            // line 104
            echo "        </span>
    </span>

    ";
            $context["visitorColumnContent"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
            // line 108
            echo "
    ";
            // line 109
            ob_start();
            // line 110
            echo "    <div class=\"referrer\">
        ";
            // line 111
            if (($this->getAttribute($context["visitor"], "getColumn", array(0 => "referrerType"), "method") == "website")) {
                // line 112
                echo "            ";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Referrers_ColumnWebsite")), "html", null, true);
                echo ":
            <a href=\"";
                // line 113
                echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "referrerUrl"), "method"), "html", null, true);
                echo "\" rel=\"noreferrer\" target=\"_blank\" title=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "referrerUrl"), "method"), "html", null, true);
                echo "\"
               style=\"text-decoration:underline;\">
                ";
                // line 115
                echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "referrerName"), "method"), "html", null, true);
                echo "
            </a>
        ";
            }
            // line 118
            echo "        ";
            if (($this->getAttribute($context["visitor"], "getColumn", array(0 => "referrerType"), "method") == "campaign")) {
                // line 119
                echo "            ";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Referrers_ColumnCampaign")), "html", null, true);
                echo "
            <br/>
            ";
                // line 121
                echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "referrerName"), "method"), "html", null, true);
                echo "
            ";
                // line 122
                if ( !twig_test_empty($this->getAttribute($context["visitor"], "getColumn", array(0 => "referrerKeyword"), "method"))) {
                    echo " - ";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "referrerKeyword"), "method"), "html", null, true);
                }
                // line 123
                echo "        ";
            }
            // line 124
            echo "        ";
            if (($this->getAttribute($context["visitor"], "getColumn", array(0 => "referrerType"), "method") == "search")) {
                // line 125
                $context["keywordNotDefined"] = call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_NotDefined", call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_ColumnKeyword"))));
                // line 126
                $context["showKeyword"] = ( !twig_test_empty($this->getAttribute($context["visitor"], "getColumn", array(0 => "referrerKeyword"), "method")) && ($this->getAttribute($context["visitor"], "getColumn", array(0 => "referrerKeyword"), "method") != $this->getContext($context, "keywordNotDefined")));
                // line 127
                if ($this->getAttribute($context["visitor"], "getColumn", array(0 => "searchEngineIcon"), "method")) {
                    // line 128
                    echo "                <img src=\"";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "searchEngineIcon"), "method"), "html", null, true);
                    echo "\" alt=\"";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "referrerName"), "method"), "html", null, true);
                    echo "\"/>
            ";
                }
                // line 130
                echo "            <span ";
                if ( !$this->getContext($context, "showKeyword")) {
                    echo "title=\"";
                    echo twig_escape_filter($this->env, $this->getContext($context, "keywordNotDefined"), "html", null, true);
                    echo "\"";
                }
                echo ">";
                echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "referrerName"), "method"), "html", null, true);
                echo "</span>
            ";
                // line 131
                if ($this->getContext($context, "showKeyword")) {
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Referrers_Keywords")), "html", null, true);
                    echo ":
                <br/>
                <a href=\"";
                    // line 133
                    echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "referrerUrl"), "method"), "html", null, true);
                    echo "\" rel=\"noreferrer\" target=\"_blank\" style=\"text-decoration:underline;\">
                    \"";
                    // line 134
                    echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "referrerKeyword"), "method"), "html", null, true);
                    echo "\"</a>
            ";
                }
                // line 136
                echo "            ";
                ob_start();
                echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "referrerKeyword"), "method"), "html", null, true);
                $context["keyword"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
                // line 137
                echo "            ";
                ob_start();
                echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "referrerName"), "method"), "html", null, true);
                $context["searchName"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
                // line 138
                echo "            ";
                ob_start();
                echo "#";
                echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "referrerKeywordPosition"), "method"), "html", null, true);
                $context["position"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
                // line 139
                echo "            ";
                if ($this->getAttribute($context["visitor"], "getColumn", array(0 => "referrerKeywordPosition"), "method")) {
                    // line 140
                    echo "                <span title='";
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Live_KeywordRankedOnSearchResultForThisVisitor", $this->getContext($context, "keyword"), $this->getContext($context, "position"), $this->getContext($context, "searchName"))), "html", null, true);
                    echo "' class='visitorRank'>
                                <span class='hash'>#</span>
                    ";
                    // line 142
                    echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "referrerKeywordPosition"), "method"), "html", null, true);
                    echo "
                            </span>
            ";
                }
                // line 145
                echo "        ";
            }
            // line 146
            echo "        ";
            if (($this->getAttribute($context["visitor"], "getColumn", array(0 => "referrerType"), "method") == "direct")) {
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Referrers_DirectEntry")), "html", null, true);
            }
            // line 147
            echo "    </div>
    ";
            $context["referrerColumnContent"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
            // line 149
            echo "
    ";
            // line 150
            ob_start();
            // line 151
            echo "        <tr class=\"label";
            echo twig_escape_filter($this->env, twig_cycle(array(0 => "odd", 1 => "even"), $this->getContext($context, "cycleIndex")), "html", null, true);
            echo "\">
        ";
            // line 152
            $context["cycleIndex"] = ($this->getContext($context, "cycleIndex") + 1);
            // line 153
            echo "            <td style=\"display:none;\"></td>
            <td class=\"label\">
                <strong title=\"";
            // line 155
            if (($this->getAttribute($context["visitor"], "getColumn", array(0 => "visitorType"), "method") == "new")) {
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_NewVisitor")), "html", null, true);
            } else {
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Live_VisitorsLastVisit", $this->getAttribute($context["visitor"], "getColumn", array(0 => "daysSinceLastVisit"), "method"))), "html", null, true);
            }
            echo "\">
                    ";
            // line 156
            echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "serverDatePrettyFirstAction"), "method"), "html", null, true);
            echo "
                    ";
            // line 157
            if ($this->getContext($context, "isWidget")) {
                echo "<br/>";
            } else {
                echo "-";
            }
            echo " ";
            echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "serverTimePrettyFirstAction"), "method"), "html", null, true);
            echo "</strong>
                ";
            // line 158
            if ( !twig_test_empty($this->getAttribute($context["visitor"], "getColumn", array(0 => "visitIp"), "method"))) {
                // line 159
                echo "                    <br/>
                <span title=\"";
                // line 160
                if ( !twig_test_empty($this->getAttribute($context["visitor"], "getColumn", array(0 => "userId"), "method"))) {
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_UserId")), "html", null, true);
                    echo ": ";
                    echo $this->getAttribute($context["visitor"], "getColumn", array(0 => "userId"), "method");
                }
                // line 161
                echo "
";
                // line 162
                if ( !twig_test_empty($this->getAttribute($context["visitor"], "getColumn", array(0 => "visitorId"), "method"))) {
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_VisitorID")), "html", null, true);
                    echo ": ";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "visitorId"), "method"), "html", null, true);
                }
                // line 163
                if (($this->getAttribute($context["visitor"], "getColumn", array(0 => "latitude"), "method") || $this->getAttribute($context["visitor"], "getColumn", array(0 => "longitude"), "method"))) {
                    // line 164
                    echo "
";
                    // line 165
                    echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "location"), "method"), "html", null, true);
                    echo "

GPS (lat/long): ";
                    // line 167
                    echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "latitude"), "method"), "html", null, true);
                    echo ",";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "longitude"), "method"), "html", null, true);
                }
                echo "\">
                    IP: ";
                // line 168
                echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "visitIp"), "method"), "html", null, true);
                echo "
                    ";
                // line 169
                if ( !twig_test_empty($this->getAttribute($context["visitor"], "getColumn", array(0 => "userId"), "method"))) {
                    echo "<br/><br/>";
                    echo $this->getAttribute($context["visitor"], "getColumn", array(0 => "userId"), "method");
                }
                // line 170
                echo "
                    </span>";
            }
            // line 172
            echo "
                ";
            // line 173
            if ($this->getAttribute($context["visitor"], "getColumn", array(0 => "provider"), "method")) {
                // line 174
                echo "                    <br/>
                    ";
                // line 175
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Provider_ColumnProvider")), "html", null, true);
                echo ":
                    <a href=\"";
                // line 176
                echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "providerUrl"), "method"), "html", null, true);
                echo "\" rel=\"noreferrer\" target=\"_blank\" title=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "providerName"), "method"), "html", null, true);
                echo " ";
                echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "providerUrl"), "method"), "html", null, true);
                echo "\" style=\"text-decoration:underline;\">
                        ";
                // line 177
                echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "providerName"), "method"), "html", null, true);
                echo "
                    </a>
                ";
            }
            // line 180
            echo "                ";
            if ($this->getAttribute($context["visitor"], "getColumn", array(0 => "customVariables"), "method")) {
                // line 181
                echo "                    <br/>
                    ";
                // line 182
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["visitor"], "getColumn", array(0 => "customVariables"), "method"));
                foreach ($context['_seq'] as $context["id"] => $context["customVariable"]) {
                    // line 183
                    echo "                        ";
                    $context["name"] = ("customVariableName" . $context["id"]);
                    // line 184
                    echo "                        ";
                    $context["value"] = ("customVariableValue" . $context["id"]);
                    // line 185
                    echo "                        <br/>
                        <acronym title=\"";
                    // line 186
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CustomVariables_CustomVariables")), "html", null, true);
                    echo " (index ";
                    echo twig_escape_filter($this->env, $context["id"], "html", null, true);
                    echo ")\">
                            ";
                    // line 187
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('truncate')->getCallable(), array($this->getAttribute($context["customVariable"], $this->getContext($context, "name"), array(), "array"), 30)), "html", null, true);
                    echo "
                        </acronym>
                    ";
                    // line 189
                    if ((twig_length_filter($this->env, $this->getAttribute($context["customVariable"], $this->getContext($context, "value"), array(), "array")) > 0)) {
                        echo ": ";
                        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('truncate')->getCallable(), array($this->getAttribute($context["customVariable"], $this->getContext($context, "value"), array(), "array"), 50)), "html", null, true);
                    }
                    // line 190
                    echo "                    ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['id'], $context['customVariable'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 191
                echo "                ";
            }
            // line 192
            echo "                ";
            if ( !$this->getContext($context, "displayVisitorsInOwnColumn")) {
                // line 193
                echo "                    <br/>
                    ";
                // line 194
                echo twig_escape_filter($this->env, $this->getContext($context, "visitorColumnContent"), "html", null, true);
                echo "
                ";
            }
            // line 196
            echo "                ";
            if ( !$this->getContext($context, "displayReferrersInOwnColumn")) {
                // line 197
                echo "                    <br/>
                    ";
                // line 198
                echo twig_escape_filter($this->env, $this->getContext($context, "referrerColumnContent"), "html", null, true);
                echo "
                ";
            }
            // line 200
            echo "            </td>

            ";
            // line 202
            if ($this->getContext($context, "displayVisitorsInOwnColumn")) {
                // line 203
                echo "                <td class=\"label\">
                    ";
                // line 204
                echo twig_escape_filter($this->env, $this->getContext($context, "visitorColumnContent"), "html", null, true);
                echo "
                </td>
            ";
            }
            // line 207
            echo "
            ";
            // line 208
            if ($this->getContext($context, "displayReferrersInOwnColumn")) {
                // line 209
                echo "                <td class=\"column\">
                    ";
                // line 210
                echo twig_escape_filter($this->env, $this->getContext($context, "referrerColumnContent"), "html", null, true);
                echo "
                </td>
            ";
            }
            // line 213
            echo "
            <td class=\"column ";
            // line 214
            if (($this->getAttribute($context["visitor"], "getColumn", array(0 => "visitConverted"), "method") &&  !$this->getContext($context, "isWidget"))) {
                echo "highlightField";
            }
            echo "\">
                <div class=\"visitor-log-page-list\">
                    ";
            // line 216
            if (( !twig_test_empty($this->getAttribute($context["visitor"], "getColumn", array(0 => "visitorId"), "method")) &&  !$this->getAttribute($this->getContext($context, "clientSideParameters"), "hideProfileLink", array()))) {
                // line 217
                echo "                    <a class=\"visitor-log-visitor-profile-link\" title=\"";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Live_ViewVisitorProfile")), "html", null, true);
                echo "\" data-visitor-id=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "visitorId"), "method"), "html", null, true);
                echo "\">
                        <img src=\"plugins/Live/images/visitorProfileLaunch.png\"/> <span>";
                // line 218
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Live_ViewVisitorProfile")), "html", null, true);
                // line 219
                if ( !twig_test_empty($this->getAttribute($context["visitor"], "getColumn", array(0 => "userId"), "method"))) {
                    echo ": ";
                    echo $this->getAttribute($context["visitor"], "getColumn", array(0 => "userId"), "method");
                }
                echo "</span>
                    </a>
                    ";
            }
            // line 222
            echo "                    <strong>
                        ";
            // line 223
            echo twig_escape_filter($this->env, twig_length_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "actionDetails"), "method")), "html", null, true);
            echo "
                        ";
            // line 224
            if ((twig_length_filter($this->env, $this->getAttribute($context["visitor"], "getColumn", array(0 => "actionDetails"), "method")) <= 1)) {
                // line 225
                echo "                            ";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Action")), "html", null, true);
                echo "
                        ";
            } else {
                // line 227
                echo "                            ";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Actions")), "html", null, true);
                echo "
                        ";
            }
            // line 229
            echo "                        ";
            if (($this->getAttribute($context["visitor"], "getColumn", array(0 => "visitDuration"), "method") > 0)) {
                echo "- ";
                echo $this->getAttribute($context["visitor"], "getColumn", array(0 => "visitDurationPretty"), "method");
            }
            // line 230
            echo "                    </strong>
                    <br/>
                    <ol class='visitorLog'>
                        ";
            // line 233
            $this->env->loadTemplate("@Live/_actionsList.twig")->display(array_merge($context, array("actionDetails" => $this->getAttribute($context["visitor"], "getColumn", array(0 => "actionDetails"), "method"))));
            // line 234
            echo "                    </ol>
                </div>
            </td>
        </tr>
    ";
            $context["visitorRow"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
            // line 239
            echo "
    ";
            // line 240
            echo twig_escape_filter($this->env, $this->getContext($context, "visitorRow"), "html", null, true);
            echo "
";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['visitor'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 242
        echo "
</tbody>
</table>";
    }

    public function getTemplateName()
    {
        return "@Live/_dataTableViz_visitorLog.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  777 => 242,  761 => 240,  758 => 239,  751 => 234,  749 => 233,  744 => 230,  738 => 229,  732 => 227,  726 => 225,  724 => 224,  720 => 223,  717 => 222,  708 => 219,  706 => 218,  699 => 217,  697 => 216,  690 => 214,  687 => 213,  681 => 210,  678 => 209,  676 => 208,  673 => 207,  667 => 204,  664 => 203,  662 => 202,  658 => 200,  653 => 198,  650 => 197,  647 => 196,  642 => 194,  639 => 193,  636 => 192,  633 => 191,  627 => 190,  622 => 189,  617 => 187,  611 => 186,  608 => 185,  605 => 184,  602 => 183,  598 => 182,  595 => 181,  592 => 180,  586 => 177,  578 => 176,  574 => 175,  571 => 174,  569 => 173,  566 => 172,  562 => 170,  557 => 169,  553 => 168,  546 => 167,  541 => 165,  538 => 164,  536 => 163,  530 => 162,  527 => 161,  521 => 160,  518 => 159,  516 => 158,  506 => 157,  502 => 156,  494 => 155,  490 => 153,  488 => 152,  483 => 151,  481 => 150,  478 => 149,  474 => 147,  469 => 146,  466 => 145,  460 => 142,  454 => 140,  451 => 139,  445 => 138,  440 => 137,  435 => 136,  430 => 134,  426 => 133,  420 => 131,  409 => 130,  401 => 128,  399 => 127,  397 => 126,  395 => 125,  392 => 124,  389 => 123,  384 => 122,  380 => 121,  374 => 119,  371 => 118,  365 => 115,  358 => 113,  353 => 112,  351 => 111,  348 => 110,  346 => 109,  343 => 108,  337 => 104,  332 => 101,  323 => 100,  314 => 99,  306 => 98,  300 => 97,  295 => 95,  292 => 94,  289 => 93,  280 => 89,  275 => 87,  272 => 86,  269 => 85,  264 => 82,  261 => 81,  248 => 79,  244 => 78,  238 => 77,  232 => 76,  226 => 75,  221 => 73,  218 => 72,  215 => 71,  210 => 68,  201 => 67,  193 => 66,  187 => 65,  182 => 63,  179 => 62,  177 => 61,  172 => 58,  164 => 56,  161 => 55,  158 => 53,  151 => 51,  148 => 50,  146 => 49,  142 => 48,  137 => 46,  131 => 45,  126 => 44,  123 => 43,  120 => 41,  112 => 38,  108 => 37,  105 => 36,  103 => 35,  97 => 31,  94 => 30,  91 => 29,  88 => 28,  71 => 27,  69 => 26,  61 => 21,  58 => 20,  52 => 17,  49 => 16,  46 => 15,  40 => 12,  37 => 11,  35 => 10,  30 => 8,  23 => 3,  21 => 2,  19 => 1,);
    }
}
