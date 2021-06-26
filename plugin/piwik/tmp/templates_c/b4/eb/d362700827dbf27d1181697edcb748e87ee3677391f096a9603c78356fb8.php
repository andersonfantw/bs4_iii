<?php

/* @Widgetize/index.twig */
class __TwigTemplate_b4ebd362700827dbf27d1181697edcb748e87ee3677391f096a9603c78356fb8 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        try {
            $this->parent = $this->env->loadTemplate("user.twig");
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
        return "user.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_content($context, array $blocks = array())
    {
        // line 4
        echo "
<div>

    <script type=\"text/javascript\">
    \$(function () {
        var widgetized = new widgetize();
        var urlPath = document.location.protocol + '//' + document.location.hostname + (document.location.port == '' ? '' : (':' + document.location.port)) + document.location.pathname;
        var dashboardUrl = urlPath + '?module=Widgetize&action=iframe&moduleToWidgetize=Dashboard&actionToWidgetize=index&idSite=' + piwik.idSite + '&period=week&date=yesterday';
        \$('#exportFullDashboard').html(
                widgetized.getInputFormWithHtml('dashboardEmbed', '<iframe src=\"' + dashboardUrl + '\" frameborder=\"0\" marginheight=\"0\" marginwidth=\"0\" width=\"100%\" height=\"100%\"></iframe>')
        );
        \$('#linkDashboardUrl').attr('href', dashboardUrl);

        var allWebsitesDashboardUrl = urlPath + '?module=Widgetize&action=iframe&moduleToWidgetize=MultiSites&actionToWidgetize=standalone&idSite=' + piwik.idSite + '&period=week&date=yesterday';
        \$('#exportAllWebsitesDashboard').html(
                widgetized.getInputFormWithHtml('allWebsitesDashboardEmbed', '<iframe src=\"' + allWebsitesDashboardUrl + '\" frameborder=\"0\" marginheight=\"0\" marginwidth=\"0\" width=\"100%\" height=\"100%\"></iframe>')
        );
        \$('#linkAllWebsitesDashboardUrl').attr('href', allWebsitesDashboardUrl);
        \$('#widgetPreview').widgetPreview({
            onPreviewLoaded: widgetized.callbackAddExportButtonsUnderWidget
        });
        broadcast.init();
    });
</script>

<h2 piwik-enriched-headline>";
        // line 29
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Widgets")), "html", null, true);
        echo "</h2>

";
        // line 31
        $this->env->loadTemplate("@CoreHome/_siteSelectHeader.twig")->display($context);
        // line 32
        echo "
<div class=\"widgetize\">
    <p>With Piwik, you can export your Web Analytics reports on your blog, website, or intranet dashboard... in one click.

    <h2>Authentication</h2>
    <p>
        If you want your widgets to be viewable by everybody, you first have to set the 'view' permissions
        to the anonymous user in the <a href='index.php?module=UsersManager' rel='noreferrer' target='_blank'>Users Management section</a>.
        <br/>Alternatively, if you are publishing widgets on a password protected or private page,
        you don't necessarily have to allow 'anonymous' to view your reports. In this case, you can add the secret token_auth parameter (found in the
        <a href='";
        // line 42
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('linkTo')->getCallable(), array(array("module" => "API", "action" => "listAllAPI"))), "html", null, true);
        echo "' rel='noreferrer' target='_blank'>API page</a>) in the widget URL.
    </p>

    <h2>Widgetize dashboards</h2>
    <p>You can also display the full Piwik dashboard in your application or website in an IFRAME
        (<a href='' rel='noreferrer' target='_blank' id='linkDashboardUrl'>see example</a>).
        The date parameter can be set to a specific calendar date, \"today\", or \"yesterday\". The period parameter can be set to \"day\", \"week\", \"month\", or
        \"year\".
        The language parameter can be set to the language code of a translation, such as language=fr.
        For example, for idSite=1 and date=yesterday, you can write: <span id='exportFullDashboard'></span>
        <br />
        You can also widgetize the all websites dashboard in an IFRAME (<a href='' rel='noreferrer' target='_blank' id='linkAllWebsitesDashboardUrl'>see example</a>)
        <span id='exportAllWebsitesDashboard'></span>
    </p>

    <h2>Widgetize reports</h2>
    <p>Select a report, and copy paste in your page the embed code below the widget:

    <div class=\"top_controls\">
        ";
        // line 61
        $this->env->loadTemplate("@CoreHome/_periodSelect.twig")->display($context);
        // line 62
        echo "    </div>

    <div id=\"widgetPreview\"></div>

    <div id='iframeDivToExport' style='display:none;'></div>
</div>
</div>

";
        // line 70
        $this->env->loadTemplate("@Dashboard/_widgetFactoryTemplate.twig")->display($context);
        // line 71
        echo "
";
    }

    public function getTemplateName()
    {
        return "@Widgetize/index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  121 => 71,  119 => 70,  109 => 62,  107 => 61,  85 => 42,  73 => 32,  71 => 31,  66 => 29,  39 => 4,  36 => 3,  11 => 1,);
    }
}
