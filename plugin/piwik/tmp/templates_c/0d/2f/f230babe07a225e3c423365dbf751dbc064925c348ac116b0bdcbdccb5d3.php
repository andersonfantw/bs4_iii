<?php

/* @CoreAdminHome/generalSettings.twig */
class __TwigTemplate_0d2ff230babe07a225e3c423365dbf751dbc064925c348ac116b0bdcbdccb5d3 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        try {
            $this->parent = $this->env->loadTemplate("admin.twig");
        } catch (Twig_Error_Loader $e) {
            $e->setTemplateFile($this->getTemplateName());
            $e->setTemplateLine(1);

            throw $e;
        }

        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "admin.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_content($context, array $blocks = array())
    {
        // line 5
        $context["piwik"] = $this->env->loadTemplate("macros.twig");
        // line 6
        $context["ajax"] = $this->env->loadTemplate("ajaxMacros.twig");
        // line 7
        echo "
";
        // line 8
        if ($this->getContext($context, "isSuperUser")) {
            // line 9
            echo "    ";
            echo $context["ajax"]->geterrorDiv();
            echo "
    ";
            // line 10
            echo $context["ajax"]->getloadingDiv();
            echo "

    <h2>";
            // line 12
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_ArchivingSettings")), "html", null, true);
            echo "</h2>
    <table class=\"adminTable\" style='width:900px;'>

    ";
            // line 15
            if ($this->getContext($context, "isGeneralSettingsAdminEnabled")) {
                // line 16
                echo "        <tr>
            <td style=\"width:400px;\">";
                // line 17
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_AllowPiwikArchivingToTriggerBrowser")), "html", null, true);
                echo "</td>
            <td style=\"width:220px;\">
                <fieldset>
                    <input id=\"enableBrowserTriggerArchiving-yes\" type=\"radio\" value=\"1\" name=\"enableBrowserTriggerArchiving\"";
                // line 20
                if (($this->getContext($context, "enableBrowserTriggerArchiving") == 1)) {
                    echo " checked=\"checked\"";
                }
                echo " />
                    <label for=\"enableBrowserTriggerArchiving-yes\">";
                // line 21
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Yes")), "html", null, true);
                echo "</label><br/>
                    <span class=\"form-description\">";
                // line 22
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Default")), "html", null, true);
                echo "</span>
                    <br/><br/>

                    <input id=\"enableBrowserTriggerArchiving-no\" type=\"radio\" value=\"0\" name=\"enableBrowserTriggerArchiving\"";
                // line 25
                if (($this->getContext($context, "enableBrowserTriggerArchiving") == 0)) {
                    echo " checked=\"checked\"";
                }
                echo " />
                    <label for=\"enableBrowserTriggerArchiving-no\">";
                // line 26
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_No")), "html", null, true);
                echo "</label><br/>
                    <span class=\"form-description\">";
                // line 27
                echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_ArchivingTriggerDescription", "<a href='?module=Proxy&action=redirect&url=http://piwik.org/docs/setup-auto-archiving/' target='_blank'>", "</a>"));
                echo "</span>
                </fieldset>
            <td>
                ";
                // line 30
                ob_start();
                // line 31
                echo "                    ";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_ArchivingInlineHelp")), "html", null, true);
                echo "
                    <br/>
                    ";
                // line 33
                echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_SeeTheOfficialDocumentationForMoreInformation", "<a href='?module=Proxy&action=redirect&url=http://piwik.org/docs/setup-auto-archiving/' target='_blank'>", "</a>"));
                echo "
                ";
                $context["browserArchivingHelp"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
                // line 35
                echo "                ";
                echo $context["piwik"]->getinlineHelp($this->getContext($context, "browserArchivingHelp"));
                echo "
            </td>
        </tr>
    ";
            } else {
                // line 39
                echo "        <tr>
            <td style=\"width:400px;\">";
                // line 40
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_AllowPiwikArchivingToTriggerBrowser")), "html", null, true);
                echo "</td>
            <td style=\"width:220px;\">
                <input id=\"enableBrowserTriggerArchiving-disabled\" type=\"radio\" checked=\"checked\" disabled=\"disabled\" />
                <label for=\"enableBrowserTriggerArchiving-disabled\">";
                // line 43
                if (($this->getContext($context, "enableBrowserTriggerArchiving") == 1)) {
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Yes")), "html", null, true);
                } else {
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_No")), "html", null, true);
                }
                echo "</label><br/>
            </td>
        </tr>
    ";
            }
            // line 47
            echo "
    <tr>
        <td width=\"400px\">
            <label for=\"todayArchiveTimeToLive\">
                ";
            // line 51
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_ReportsContainingTodayWillBeProcessedAtMostEvery")), "html", null, true);
            echo "
            </label>
        </td>
        <td>
            ";
            // line 55
            ob_start();
            // line 56
            echo "            <input size='3' value='";
            echo twig_escape_filter($this->env, $this->getContext($context, "todayArchiveTimeToLive"), "html", null, true);
            echo "' id='todayArchiveTimeToLive' ";
            if ( !$this->getContext($context, "isGeneralSettingsAdminEnabled")) {
                echo "disabled=\"disabled\"";
            }
            echo "/>
            ";
            $context["timeOutInput"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
            // line 58
            echo "
            ";
            // line 59
            echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_NSeconds", $this->getContext($context, "timeOutInput")));
            echo "
        </td>

        ";
            // line 62
            if ($this->getContext($context, "isGeneralSettingsAdminEnabled")) {
                // line 63
                echo "            <td width='450px'>
                ";
                // line 64
                ob_start();
                // line 65
                echo "                    ";
                if ($this->getContext($context, "showWarningCron")) {
                    // line 66
                    echo "                        <strong>
                            ";
                    // line 67
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_NewReportsWillBeProcessedByCron")), "html", null, true);
                    echo "<br/>
                            ";
                    // line 68
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_ReportsWillBeProcessedAtMostEveryHour")), "html", null, true);
                    echo "
                            ";
                    // line 69
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_IfArchivingIsFastYouCanSetupCronRunMoreOften")), "html", null, true);
                    echo "<br/>
                        </strong>
                    ";
                }
                // line 72
                echo "                    ";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_SmallTrafficYouCanLeaveDefault", $this->getContext($context, "todayArchiveTimeToLiveDefault"))), "html", null, true);
                echo "
                    <br/>
                    ";
                // line 74
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_MediumToHighTrafficItIsRecommendedTo", 1800, 3600)), "html", null, true);
                echo "
                ";
                $context["archiveTodayTTLHelp"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
                // line 76
                echo "                ";
                echo $context["piwik"]->getinlineHelp($this->getContext($context, "archiveTodayTTLHelp"));
                echo "
            </td>
        ";
            }
            // line 79
            echo "    </tr>

    ";
            // line 81
            if ($this->getContext($context, "isGeneralSettingsAdminEnabled")) {
                // line 82
                echo "        <tr>
            <td colspan=\"3\">

                <h2>";
                // line 85
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_UpdateSettings")), "html", null, true);
                echo "</h2>
            </td>
        </tr>
        <tr>
            <td style=\"width:400px;\">";
                // line 89
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_CheckReleaseGetVersion")), "html", null, true);
                echo "</td>
            <td style=\"width:220px;\">
                <fieldset>
                    <input id=\"enableBetaReleaseCheck-0\" type=\"radio\" value=\"0\" name=\"enableBetaReleaseCheck\"";
                // line 92
                if (($this->getContext($context, "enableBetaReleaseCheck") == 0)) {
                    echo " checked=\"checked\"";
                }
                echo " />
                    <label for=\"enableBetaReleaseCheck-0\">";
                // line 93
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_LatestStableRelease")), "html", null, true);
                echo "</label><br/>
                    <span class=\"form-description\">";
                // line 94
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Recommended")), "html", null, true);
                echo "</span>
                    <br/><br/>

                    <input id=\"enableBetaReleaseCheck-1\" type=\"radio\" value=\"1\" name=\"enableBetaReleaseCheck\"";
                // line 97
                if (($this->getContext($context, "enableBetaReleaseCheck") == 1)) {
                    echo " checked=\"checked\"";
                }
                echo " />
                    <label for=\"enableBetaReleaseCheck-1\">";
                // line 98
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_LatestBetaRelease")), "html", null, true);
                echo "</label><br/>
                    <span class=\"form-description\">";
                // line 99
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_ForBetaTestersOnly")), "html", null, true);
                echo "</span>
                </fieldset>
            <td>
                ";
                // line 102
                ob_start();
                // line 103
                echo "                    ";
                echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_DevelopmentProcess", "<a href='?module=Proxy&action=redirect&url=http://piwik.org/participate/development-process/' target='_blank'>", "</a>"));
                echo "
                    <br/>
                    ";
                // line 105
                echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_StableReleases", "<a href='?module=Proxy&action=redirect&url=http://piwik.org/participate/user-feedback/' target='_blank'>", "</a>"));
                echo "
                ";
                $context["checkReleaseHelp"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
                // line 107
                echo "                ";
                echo $context["piwik"]->getinlineHelp($this->getContext($context, "checkReleaseHelp"));
                echo "
            </td>
        </tr>

        ";
                // line 111
                if ($this->getContext($context, "canUpdateCommunication")) {
                    // line 112
                    echo "
            <tr>
                <td style=\"width:400px;\">";
                    // line 114
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_SendPluginUpdateCommunication")), "html", null, true);
                    echo "</td>
                <td style=\"width:220px;\">
                    <fieldset>
                        <input id=\"enablePluginUpdateCommunication-1\" type=\"radio\"
                               name=\"enablePluginUpdateCommunication\" value=\"1\"
                                ";
                    // line 119
                    if (($this->getContext($context, "enableSendPluginUpdateCommunication") == 1)) {
                        echo " checked=\"checked\"";
                    }
                    echo "/>
                        <label for=\"enablePluginUpdateCommunication-1\">";
                    // line 120
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Yes")), "html", null, true);
                    echo "</label>
                        <br />
                        <br />
                        <input class=\"indented-radio-button\" id=\"enablePluginUpdateCommunication-0\" type=\"radio\"
                               name=\"enablePluginUpdateCommunication\" value=\"0\"
                               ";
                    // line 125
                    if (($this->getContext($context, "enableSendPluginUpdateCommunication") == 0)) {
                        echo " checked=\"checked\"";
                    }
                    echo "/>
                        <label for=\"enablePluginUpdateCommunication-0\">";
                    // line 126
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_No")), "html", null, true);
                    echo "</label>
                        <br />
                        <span class=\"form-description\">";
                    // line 128
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Default")), "html", null, true);
                    echo "</span>
                    </fieldset>
                <td>
                    ";
                    // line 131
                    echo $context["piwik"]->getinlineHelp(call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_SendPluginUpdateCommunicationHelp")));
                    echo "
                </td>
            </tr>

        ";
                }
                // line 136
                echo "
    ";
            }
            // line 138
            echo "    </table>

    ";
            // line 140
            if ($this->getContext($context, "isGeneralSettingsAdminEnabled")) {
                // line 141
                echo "        <h2>";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_EmailServerSettings")), "html", null, true);
                echo "</h2>
        <div id='emailSettings'>
            <table class=\"adminTable\" style='width:600px;'>
                <tr>
                    <td>";
                // line 145
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_UseSMTPServerForEmail")), "html", null, true);
                echo "<br/>
                        <span class=\"form-description\">";
                // line 146
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_SelectYesIfYouWantToSendEmailsViaServer")), "html", null, true);
                echo "</span>
                    </td>
                    <td style=\"width:200px;\">
                        <input id=\"mailUseSmtp-1\" type=\"radio\" name=\"mailUseSmtp\" value=\"1\" ";
                // line 149
                if (($this->getAttribute($this->getContext($context, "mail"), "transport", array()) == "smtp")) {
                    echo " checked ";
                }
                echo "/>
                        <label for=\"mailUseSmtp-1\">";
                // line 150
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Yes")), "html", null, true);
                echo "</label>
                        <input class=\"indented-radio-button\" id=\"mailUseSmtp-0\" type=\"radio\" name=\"mailUseSmtp\" value=\"0\"
                               ";
                // line 152
                if (($this->getAttribute($this->getContext($context, "mail"), "transport", array()) == "")) {
                    echo " checked ";
                }
                echo "/>
                        <label for=\"mailUseSmtp-0\">";
                // line 153
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_No")), "html", null, true);
                echo "</label>
                    </td>
                </tr>
            </table>
        </div>
        <div id='smtpSettings'>
            <table class=\"adminTable\" style='width:550px;'>
                <tr>
                    <td><label for=\"mailHost\">";
                // line 161
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_SmtpServerAddress")), "html", null, true);
                echo "</label></td>
                    <td style=\"width:200px;\"><input type=\"text\" id=\"mailHost\" value=\"";
                // line 162
                echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "mail"), "host", array()), "html", null, true);
                echo "\"></td>
                </tr>
                <tr>
                    <td><label for=\"mailPort\">";
                // line 165
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_SmtpPort")), "html", null, true);
                echo "</label><br/>
                        <span class=\"form-description\">";
                // line 166
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_OptionalSmtpPort")), "html", null, true);
                echo "</span></td>
                    <td><input type=\"text\" id=\"mailPort\" value=\"";
                // line 167
                echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "mail"), "port", array()), "html", null, true);
                echo "\"></td>
                </tr>
                <tr>
                    <td><label for=\"mailType\">";
                // line 170
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_AuthenticationMethodSmtp")), "html", null, true);
                echo "</label><br/>
                        <span class=\"form-description\">";
                // line 171
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_OnlyUsedIfUserPwdIsSet")), "html", null, true);
                echo "</span>
                    </td>
                    <td>
                        <select id=\"mailType\">
                            <option value=\"\" ";
                // line 175
                if (($this->getAttribute($this->getContext($context, "mail"), "type", array()) == "")) {
                    echo " selected=\"selected\" ";
                }
                echo "></option>
                            <option id=\"plain\" ";
                // line 176
                if (($this->getAttribute($this->getContext($context, "mail"), "type", array()) == "Plain")) {
                    echo " selected=\"selected\" ";
                }
                echo " value=\"Plain\">Plain</option>
                            <option id=\"login\" ";
                // line 177
                if (($this->getAttribute($this->getContext($context, "mail"), "type", array()) == "Login")) {
                    echo " selected=\"selected\" ";
                }
                echo " value=\"Login\"> Login</option>
                            <option id=\"cram-md5\" ";
                // line 178
                if (($this->getAttribute($this->getContext($context, "mail"), "type", array()) == "Crammd5")) {
                    echo " selected=\"selected\" ";
                }
                echo " value=\"Crammd5\"> Crammd5</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for=\"mailUsername\">";
                // line 183
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_SmtpUsername")), "html", null, true);
                echo "</label><br/>
                        <span class=\"form-description\">";
                // line 184
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_OnlyEnterIfRequired")), "html", null, true);
                echo "</span></td>
                    <td>
                        <input type=\"text\" id=\"mailUsername\" value=\"";
                // line 186
                echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "mail"), "username", array()), "html", null, true);
                echo "\"/>
                    </td>
                </tr>
                <tr>
                    <td><label for=\"mailPassword\">";
                // line 190
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_SmtpPassword")), "html", null, true);
                echo "</label><br/>
                    <span class=\"form-description\">";
                // line 191
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_OnlyEnterIfRequiredPassword")), "html", null, true);
                echo "<br/>
                        ";
                // line 192
                echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_WarningPasswordStored", "<strong>", "</strong>"));
                echo "</span>
                    </td>
                    <td>
                        <input type=\"password\" id=\"mailPassword\" value=\"";
                // line 195
                echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "mail"), "password", array()), "html", null, true);
                echo "\"/>
                    </td>
                </tr>
                <tr>
                    <td><label for=\"mailEncryption\">";
                // line 199
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_SmtpEncryption")), "html", null, true);
                echo "</label><br/>
                        <span class=\"form-description\">";
                // line 200
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_EncryptedSmtpTransport")), "html", null, true);
                echo "</span></td>
                    <td>
                        <select id=\"mailEncryption\">
                            <option value=\"\" ";
                // line 203
                if (($this->getAttribute($this->getContext($context, "mail"), "encryption", array()) == "")) {
                    echo " selected=\"selected\" ";
                }
                echo "></option>
                            <option id=\"ssl\" ";
                // line 204
                if (($this->getAttribute($this->getContext($context, "mail"), "encryption", array()) == "ssl")) {
                    echo " selected=\"selected\" ";
                }
                echo " value=\"ssl\">SSL</option>
                            <option id=\"tls\" ";
                // line 205
                if (($this->getAttribute($this->getContext($context, "mail"), "encryption", array()) == "tls")) {
                    echo " selected=\"selected\" ";
                }
                echo " value=\"tls\">TLS</option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
    ";
            }
            // line 212
            echo "
    <h2>";
            // line 213
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_BrandingSettings")), "html", null, true);
            echo "</h2>
    <div id='brandSettings'>
        ";
            // line 215
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_CustomLogoHelpText")), "html", null, true);
            echo "
        <table class=\"adminTable\" style=\"width:900px;\">
            <tr>
                <td style=\"width:200px;\">";
            // line 218
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_UseCustomLogo")), "html", null, true);
            echo "</td>
                <td style=\"width:200px;\">
                    <input id=\"useCustomLogo-1\" type=\"radio\" name=\"useCustomLogo\" value=\"1\" ";
            // line 220
            if (($this->getAttribute($this->getContext($context, "branding"), "use_custom_logo", array()) == 1)) {
                echo " checked ";
            }
            echo "/>
                    <label for=\"useCustomLogo-1\">";
            // line 221
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Yes")), "html", null, true);
            echo "</label>
                    <input class=\"indented-radio-button\" id=\"useCustomLogo-0\" type=\"radio\" name=\"useCustomLogo\" value=\"0\" ";
            // line 222
            if (($this->getAttribute($this->getContext($context, "branding"), "use_custom_logo", array()) == 0)) {
                echo " checked ";
            }
            echo " />
                    <label for=\"useCustomLogo-0\" class>";
            // line 223
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_No")), "html", null, true);
            echo "</label>
                </td>
                <td id=\"inlineHelpCustomLogo\">
                    ";
            // line 226
            ob_start();
            echo "\"";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_GiveUsYourFeedback")), "html", null, true);
            echo "\"";
            $context["giveUsFeedbackText"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
            // line 227
            echo "                    ";
            ob_start();
            // line 228
            echo "                    ";
            echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_CustomLogoFeedbackInfo", $this->getContext($context, "giveUsFeedbackText"), "<a href='?module=CorePluginsAdmin&action=plugins' rel='noreferrer' target='_blank'>", "</a>"));
            echo "
                    ";
            $context["customLogoHelp"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
            // line 230
            echo "                    ";
            echo $context["piwik"]->getinlineHelp($this->getContext($context, "customLogoHelp"));
            echo "
                </td>
            </tr>
        </table>
    </div>
    <div id='logoSettings'>
        <form id=\"logoUploadForm\" method=\"post\" enctype=\"multipart/form-data\" action=\"index.php?module=CoreAdminHome&format=json&action=uploadCustomLogo\">
            <table class=\"adminTable\" style='width:550px;'>
                ";
            // line 238
            if ($this->getContext($context, "fileUploadEnabled")) {
                // line 239
                echo "                    ";
                if ($this->getContext($context, "logosWriteable")) {
                    // line 240
                    echo "                        <tr>
                            <td>
                                <label for=\"customLogo\">";
                    // line 242
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_LogoUpload")), "html", null, true);
                    echo ":<br/>
                                    <span class=\"form-description\">";
                    // line 243
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_LogoUploadHelp", "JPG / PNG / GIF", 110)), "html", null, true);
                    echo "</span>
                                </label>
                            </td>
                            <td style=\"width:200px;\">
                                <input name=\"customLogo\" type=\"file\" id=\"customLogo\"/>
                                <img src=\"";
                    // line 248
                    echo twig_escape_filter($this->env, $this->getContext($context, "pathUserLogo"), "html", null, true);
                    echo "?r=";
                    echo twig_escape_filter($this->env, twig_random($this->env), "html", null, true);
                    echo "\" id=\"currentLogo\" height=\"150\"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for=\"customLogo\">";
                    // line 253
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_FaviconUpload")), "html", null, true);
                    echo ":<br/>
                                    <span class=\"form-description\">";
                    // line 254
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_LogoUploadHelp", "JPG / PNG / GIF", 16)), "html", null, true);
                    echo "</span>
                                </label>
                            </td>
                            <td style=\"width:200px;\">
                                <input name=\"customFavicon\" type=\"file\" id=\"customFavicon\"/>
                                <img src=\"";
                    // line 259
                    echo twig_escape_filter($this->env, $this->getContext($context, "pathUserFavicon"), "html", null, true);
                    echo "?r=";
                    echo twig_escape_filter($this->env, twig_random($this->env), "html", null, true);
                    echo "\" id=\"currentFavicon\" width=\"16\" height=\"16\"/>
                            </td>
                        </tr>
                    ";
                } else {
                    // line 263
                    echo "                        <tr>
                            <td>
                                <div style=\"display:inline-block;margin-top:10px;\" id=\"CoreAdminHome_LogoNotWriteable\">
                                    ";
                    // line 266
                    echo call_user_func_array($this->env->getFilter('notification')->getCallable(), array(call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_LogoNotWriteableInstruction", (("<strong>" .                     // line 267
$this->getContext($context, "pathUserLogoDirectory")) . "</strong><br/>"), ((((($this->getContext($context, "pathUserLogo") . ", ") . $this->getContext($context, "pathUserLogoSmall")) . ", ") . $this->getContext($context, "pathUserLogoSVG")) . ""))), array("placeAt" => "#CoreAdminHome_LogoNotWriteable", "noclear" => true, "context" => "warning", "raw" => true)));
                    // line 268
                    echo "

                                </div>
                            </td>
                        </tr>
                    ";
                }
                // line 274
                echo "                ";
            } else {
                // line 275
                echo "                    <tr>
                        <td>
                            <div style=\"display:inline-block;margin-top:10px;\" id=\"CoreAdminHome_FileUploadDisabled\">
                                ";
                // line 278
                echo call_user_func_array($this->env->getFilter('notification')->getCallable(), array(call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_FileUploadDisabled", "file_uploads=1")), array("placeAt" => "#CoreAdminHome_FileUploadDisabled", "noclear" => true, "context" => "warning", "raw" => true)));
                // line 280
                echo "

                            </div>
                        </td>
                    </tr>

                ";
            }
            // line 287
            echo "            </table>
        </form>
    </div>

    <div class=\"ui-confirm\" id=\"confirmTrustedHostChange\">
        <h2>";
            // line 292
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_TrustedHostConfirm")), "html", null, true);
            echo "</h2>
        <input role=\"yes\" type=\"button\" value=\"";
            // line 293
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Yes")), "html", null, true);
            echo "\"/>
        <input role=\"no\" type=\"button\" value=\"";
            // line 294
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_No")), "html", null, true);
            echo "\"/>
    </div>
    <h2 id=\"trustedHostsSection\">";
            // line 296
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_TrustedHostSettings")), "html", null, true);
            echo "</h2>
    <div id='trustedHostSettings'>

        ";
            // line 299
            $this->env->loadTemplate("@CoreHome/_warningInvalidHost.twig")->display($context);
            // line 300
            echo "
        ";
            // line 301
            if ( !$this->getContext($context, "isGeneralSettingsAdminEnabled")) {
                // line 302
                echo "            ";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_PiwikIsInstalledAt")), "html", null, true);
                echo ": ";
                echo twig_escape_filter($this->env, twig_join_filter($this->getContext($context, "trustedHosts"), ", "), "html", null, true);
                echo "
        ";
            } else {
                // line 304
                echo "            <p>";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_PiwikIsInstalledAt")), "html", null, true);
                echo ":</p>
            <strong>";
                // line 305
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_ValidPiwikHostname")), "html", null, true);
                echo "</strong>
            <ul>
                ";
                // line 307
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getContext($context, "trustedHosts"));
                foreach ($context['_seq'] as $context["hostIdx"] => $context["host"]) {
                    // line 308
                    echo "                    <li>
                        <input name=\"trusted_host\" type=\"text\" value=\"";
                    // line 309
                    echo twig_escape_filter($this->env, $context["host"], "html", null, true);
                    echo "\"/>
                        <a href=\"#\" class=\"remove-trusted-host\" title=\"";
                    // line 310
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Delete")), "html", null, true);
                    echo "\">
                            <img alt=\"";
                    // line 311
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Delete")), "html", null, true);
                    echo "\" src=\"plugins/Morpheus/images/ico_delete.png\" />
                        </a>
                    </li>
                ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['hostIdx'], $context['host'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 315
                echo "            </ul>
            <div class=\"add-trusted-host-container\">
                <a href=\"#\" class=\"add-trusted-host\"><em>";
                // line 317
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Add")), "html", null, true);
                echo "</em></a>
            </div>
        ";
            }
            // line 320
            echo "    </div>

    <input type=\"submit\" value=\"";
            // line 322
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Save")), "html", null, true);
            echo "\" id=\"generalSettingsSubmit\" class=\"submit\"/>
    <br/>
    <br/>

    ";
            // line 326
            if ($this->getContext($context, "isDataPurgeSettingsEnabled")) {
                // line 327
                echo "    ";
                ob_start();
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_DeleteDataSettings")), "html", null, true);
                $context["clickDeleteLogSettings"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
                // line 328
                echo "    <h2>";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_DeleteDataSettings")), "html", null, true);
                echo "</h2>
    <p>
        ";
                // line 330
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_DeleteDataDescription")), "html", null, true);
                echo " ";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_DeleteDataDescription2")), "html", null, true);
                echo "
        <br/>
        <a href='";
                // line 332
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('linkTo')->getCallable(), array(array("module" => "PrivacyManager", "action" => "privacySettings"))), "html", null, true);
                echo "#deleteLogsAnchor'>
            ";
                // line 333
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_ClickHereSettings", (("'" . $this->getContext($context, "clickDeleteLogSettings")) . "'"))), "html", null, true);
                echo "
        </a>
    </p>
    ";
            }
        }
        // line 338
        echo "
";
    }

    public function getTemplateName()
    {
        return "@CoreAdminHome/generalSettings.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  831 => 338,  823 => 333,  819 => 332,  812 => 330,  806 => 328,  801 => 327,  799 => 326,  792 => 322,  788 => 320,  782 => 317,  778 => 315,  768 => 311,  764 => 310,  760 => 309,  757 => 308,  753 => 307,  748 => 305,  743 => 304,  735 => 302,  733 => 301,  730 => 300,  728 => 299,  722 => 296,  717 => 294,  713 => 293,  709 => 292,  702 => 287,  693 => 280,  691 => 278,  686 => 275,  683 => 274,  675 => 268,  673 => 267,  672 => 266,  667 => 263,  658 => 259,  650 => 254,  646 => 253,  636 => 248,  628 => 243,  624 => 242,  620 => 240,  617 => 239,  615 => 238,  603 => 230,  597 => 228,  594 => 227,  588 => 226,  582 => 223,  576 => 222,  572 => 221,  566 => 220,  561 => 218,  555 => 215,  550 => 213,  547 => 212,  535 => 205,  529 => 204,  523 => 203,  517 => 200,  513 => 199,  506 => 195,  500 => 192,  496 => 191,  492 => 190,  485 => 186,  480 => 184,  476 => 183,  466 => 178,  460 => 177,  454 => 176,  448 => 175,  441 => 171,  437 => 170,  431 => 167,  427 => 166,  423 => 165,  417 => 162,  413 => 161,  402 => 153,  396 => 152,  391 => 150,  385 => 149,  379 => 146,  375 => 145,  367 => 141,  365 => 140,  361 => 138,  357 => 136,  349 => 131,  343 => 128,  338 => 126,  332 => 125,  324 => 120,  318 => 119,  310 => 114,  306 => 112,  304 => 111,  296 => 107,  291 => 105,  285 => 103,  283 => 102,  277 => 99,  273 => 98,  267 => 97,  261 => 94,  257 => 93,  251 => 92,  245 => 89,  238 => 85,  233 => 82,  231 => 81,  227 => 79,  220 => 76,  215 => 74,  209 => 72,  203 => 69,  199 => 68,  195 => 67,  192 => 66,  189 => 65,  187 => 64,  184 => 63,  182 => 62,  176 => 59,  173 => 58,  163 => 56,  161 => 55,  154 => 51,  148 => 47,  137 => 43,  131 => 40,  128 => 39,  120 => 35,  115 => 33,  109 => 31,  107 => 30,  101 => 27,  97 => 26,  91 => 25,  85 => 22,  81 => 21,  75 => 20,  69 => 17,  66 => 16,  64 => 15,  58 => 12,  53 => 10,  48 => 9,  46 => 8,  43 => 7,  41 => 6,  39 => 5,  36 => 3,  11 => 1,);
    }
}
