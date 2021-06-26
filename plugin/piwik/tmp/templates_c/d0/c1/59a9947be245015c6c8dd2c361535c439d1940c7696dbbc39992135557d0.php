<?php

/* @CorePluginsAdmin/browsePlugins.twig */
class __TwigTemplate_d0c159a9947be245015c6c8dd2c361535c439d1940c7696dbbc39992135557d0 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return $this->env->resolveTemplate((((array_key_exists("mode", $context) && ($this->getContext($context, "mode") == "user"))) ? ("user.twig") : ("admin.twig")));
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 2
        $context["pluginsMacro"] = $this->env->loadTemplate("@CorePluginsAdmin/macros.twig");
        // line 1
        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 4
    public function block_content($context, array $blocks = array())
    {
        // line 5
        echo "
    <div class=\"pluginslistActionBar\">

        <h2 piwik-enriched-headline
            feature-name=\"";
        // line 9
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_Marketplace")), "html", null, true);
        echo "\"
            >";
        // line 10
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_TeaserExtendPiwikByPlugin")), "html", null, true);
        echo "</h2>

        <div class=\"infoBox\">
            ";
        // line 13
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_PluginsExtendPiwik")), "html", null, true);
        echo "
            ";
        // line 14
        echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_InstallingNewPluginViaMarketplaceOrUpload", "<a href=\"#\" class=\"uploadPlugin\">", "</a>"));
        echo "
            <br/>
            ";
        // line 16
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_BeCarefulUsingPlugins")), "html", null, true);
        echo "
        </div>

        ";
        // line 19
        $this->env->loadTemplate("@CorePluginsAdmin/browsePluginsActions.twig")->display($context);
        // line 20
        echo "    </div>

    ";
        // line 22
        if ( !$this->getContext($context, "isSuperUser")) {
            // line 23
            echo "        <div class=\"pluginslistNonSuperUserHint\">
            ";
            // line 24
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_NotAllowedToBrowseMarketplacePlugins")), "html", null, true);
            echo "
        </div>
    ";
        }
        // line 27
        echo "
    <div class=\"row pluginslist\">

        ";
        // line 30
        if (twig_length_filter($this->env, $this->getContext($context, "plugins"))) {
            // line 31
            echo "
            ";
            // line 32
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getContext($context, "plugins"));
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
            foreach ($context['_seq'] as $context["_key"] => $context["plugin"]) {
                // line 33
                echo "
                <div class=\"col-md-4\">
                    <div class=\"plugin\">
                        <div class=\"content\" data-pluginName=\"";
                // line 36
                echo twig_escape_filter($this->env, $this->getAttribute($context["plugin"], "name", array()), "html", null, true);
                echo "\">
                            ";
                // line 37
                $this->env->loadTemplate("@CorePluginsAdmin/pluginOverview.twig")->display($context);
                // line 38
                echo "                        </div>

                        <div class=\"footer\" data-pluginName=\"";
                // line 40
                echo twig_escape_filter($this->env, $this->getAttribute($context["plugin"], "name", array()), "html", null, true);
                echo "\">
                            ";
                // line 41
                if ($this->getAttribute($context["plugin"], "featured", array())) {
                    // line 42
                    echo "                                ";
                    echo $context["pluginsMacro"]->getfeaturedIcon("right");
                    echo "
                            ";
                }
                // line 44
                echo "                            ";
                $this->env->loadTemplate("@CorePluginsAdmin/pluginMetadata.twig")->display($context);
                // line 45
                echo "                        </div>
                    </div>
                </div>

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
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['plugin'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 50
            echo "
        ";
        } else {
            // line 52
            echo "            ";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_NoPluginsFound")), "html", null, true);
            echo "
        ";
        }
        // line 54
        echo "    </div>

    <div class=\"pluginFooterMessage\">
    ";
        // line 57
        $context["marketplaceSellPluginSubject"] = call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_MarketplaceSellPluginSubject"));
        // line 58
        echo "        ";
        echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_GetEarlyAccessForPaidPlugins", (("<a href='mailto:hello@piwik.org?subject=" . $this->getContext($context, "marketplaceSellPluginSubject")) . "'>"), "</a>"));
        echo "
        <br/>
        ";
        // line 60
        echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CorePluginsAdmin_DevelopersLearnHowToDevelopPlugins", "<a href=\"?module=Proxy&action=redirect&url=http://developer.piwik.org/plugins\" target=\"_blank\">", "</a>"));
        echo "
    </div>
";
    }

    public function getTemplateName()
    {
        return "@CorePluginsAdmin/browsePlugins.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  179 => 60,  173 => 58,  171 => 57,  166 => 54,  160 => 52,  156 => 50,  138 => 45,  135 => 44,  129 => 42,  127 => 41,  123 => 40,  119 => 38,  117 => 37,  113 => 36,  108 => 33,  91 => 32,  88 => 31,  86 => 30,  81 => 27,  75 => 24,  72 => 23,  70 => 22,  66 => 20,  64 => 19,  58 => 16,  53 => 14,  49 => 13,  43 => 10,  39 => 9,  33 => 5,  30 => 4,  26 => 1,  24 => 2,  18 => 1,);
    }
}
