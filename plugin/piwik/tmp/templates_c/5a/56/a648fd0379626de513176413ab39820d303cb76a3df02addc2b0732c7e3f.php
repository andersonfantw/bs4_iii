<?php

/* @CorePluginsAdmin/pluginOverview.twig */
class __TwigTemplate_5a56a648fd0379626de513176413ab39820d303cb76a3df02addc2b0732c7e3f extends Twig_Template
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
        $context["plugins"] = $this->env->loadTemplate("@CorePluginsAdmin/macros.twig");
        // line 2
        echo "
";
        // line 3
        if ($this->getContext($context, "isSuperUser")) {
            // line 4
            echo "    ";
            if (($this->getAttribute($this->getContext($context, "plugin"), "canBeUpdated", array()) && (0 == twig_length_filter($this->env, $this->getAttribute($this->getContext($context, "plugin"), "missingRequirements", array()))))) {
                // line 5
                echo "        <a class=\"update\"
           href=\"";
                // line 6
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('linkTo')->getCallable(), array(array("action" => "updatePlugin", "pluginName" => $this->getAttribute($this->getContext($context, "plugin"), "name", array()), "nonce" => $this->getContext($context, "updateNonce")))), "html", null, true);
                echo "\"
           >";
                // line 7
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreUpdater_UpdateTitle")), "html", null, true);
                echo "</a>
    ";
            } elseif ($this->getAttribute(            // line 8
$this->getContext($context, "plugin"), "isInstalled", array())) {
                // line 9
                echo "        <span class=\"install\">";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Installed")), "html", null, true);
                echo "</span>
    ";
            } elseif ((0 < twig_length_filter($this->env, $this->getAttribute(            // line 10
$this->getContext($context, "plugin"), "missingRequirements", array())))) {
                // line 11
                echo "    ";
            } else {
                // line 12
                echo "        <a href=\"";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('linkTo')->getCallable(), array(array("action" => "installPlugin", "pluginName" => $this->getAttribute($this->getContext($context, "plugin"), "name", array()), "nonce" => $this->getContext($context, "installNonce")))), "html", null, true);
                echo "\"
           class=\"install\">";
                // line 13
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_ActionInstall")), "html", null, true);
                echo "</a>
    ";
            }
        }
        // line 16
        echo "
<h3 class=\"header\" title=\"";
        // line 17
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_MoreDetails")), "html", null, true);
        echo "\">
    <a href=\"javascript:void(0);\" class=\"more\">";
        // line 18
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "plugin"), "name", array()), "html", null, true);
        echo "</a>
</h3>
<p class=\"description\">";
        // line 20
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "plugin"), "description", array()), "html", null, true);
        echo "
    <br />
    <a href=\"javascript:void(0);\" title=\"";
        // line 22
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_MoreDetails")), "html", null, true);
        echo "\" class=\"more\">&rsaquo; ";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_MoreLowerCase")), "html", null, true);
        echo "</a>
</p>

";
        // line 25
        if ($this->getAttribute($this->getContext($context, "plugin"), "canBeUpdated", array())) {
            // line 26
            echo "    <p class=\"updateAvailableNotice\" data-activePluginTab=\"changelog\">";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_PluginUpdateAvailable", $this->getAttribute($this->getContext($context, "plugin"), "currentVersion", array()), $this->getAttribute($this->getContext($context, "plugin"), "latestVersion", array()))), "html", null, true);
            echo "</p>
";
        }
        // line 28
        echo "
";
        // line 29
        echo $context["plugins"]->getmissingRequirementsPleaseUpdateNotice($this->getContext($context, "plugin"));
    }

    public function getTemplateName()
    {
        return "@CorePluginsAdmin/pluginOverview.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  99 => 29,  96 => 28,  90 => 26,  88 => 25,  80 => 22,  75 => 20,  70 => 18,  66 => 17,  63 => 16,  57 => 13,  52 => 12,  49 => 11,  47 => 10,  42 => 9,  40 => 8,  36 => 7,  32 => 6,  29 => 5,  26 => 4,  24 => 3,  21 => 2,  19 => 1,);
    }
}
