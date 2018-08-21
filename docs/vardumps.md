<h1>get_view() function</h1>

<h2>libraries/Omeka/View.php</h2>

```php
/**
 * Customized subclass of Zend Framework's View class.
 *
 * This adds the correct script paths for themes and plugins so that controllers 
 * can render the appropriate scripts.
 *
 * This will also inject directly into the view scripts all variables that have 
 * been assigned to the view, so that theme writers can access them as $item 
 * instead of $this->item, for example.
 * 
 * @package Omeka\View
 */
class Omeka_View extends Zend_View_Abstract
{    
    const THEME_HOOK_NAMESPACE = '__global__';

    /**
     * Maintains a key => value pairing corresponding to hard path => web path 
     * for possible assets for Omeka views.
     *
     * @var array
     */
    protected $_asset_paths = array();
    
    /**
     * Flag indicated whether theme custom scripts have been loaded.
     *
     * @var boolean
     */
    private $_customScriptsLoaded = false;
    
    /**
     * @param array $config View configuration.
     */
    public function __construct($config = array())
    {
        parent::__construct($config);         
        
        // Setting the XHTML1_STRICT doctype fixes validation errors for ZF's form elements
        $this->doctype()->setDoctype('HTML5');
        
        $this->addHelperPath(VIEW_HELPERS_DIR, 'Omeka_View_Helper');

        try {
            $mvc = Zend_Registry::get('plugin_mvc');
            foreach ($mvc->getHelpersDirs() as $pluginDirName => $dir) {
                $this->addHelperPath($dir, "{$pluginDirName}_View_Helper"); 
            }
        } catch (Zend_Exception $e) {
            // no plugins or MVC component, so we can't add helper paths
        }
    }
    
    /**
     * Get the currently-configured asset paths.
     *
     * @return array
     */
    public function getAssetPaths()
    {
        return $this->_asset_paths;
    }
    
    /**
     * Add an asset path to the view.
     *
     * @param string $physical Local filesystem path.
     * @param string $web URL path.
     * @return void
     */
    public function addAssetPath($physical, $web)
    {
        array_unshift($this->_asset_paths, array($physical, $web));
    }
    
    /**
     * Remove the existing asset paths and set a single new one.
     * 
     * @param string $physical Local filesystem path.
     * @param string $web URL path.
     * @return void 
     */
    public function setAssetPath($physical, $web)
    {
        $this->_asset_paths = array();
        $this->_asset_paths[] = array($physical, $web);
    }
        
    /**
     * Allow for variables set to the view object
     * to be referenced in the view script by their actual name.
     *
     * Also allows access to theme helpers.
     * 
     * For example, in a controller you might do something like:
     * $view->assign('themes', $themes);
     * Normally in the view you would then reference $themes through:
     * $this->themes;
     * 
     * Now you can reference it simply by using:
     * $themes;
     *
     * @return void
     */
    public function _run() {
        $this->_loadCustomThemeScripts();
        $vars = $this->getVars();
        extract($vars);
        include func_get_arg(0);
    }
    
    /**
     * Look for a 'custom.php' script in all script paths and include the file if it exists.
     * 
     * @internal This must 'include' (as opposed to 'require_once') the script because
     * it typically contains executable code that modifies global state.  These
     * scripts need to be loaded only once per request, but multiple times in
     * the test environment.  Hence the flag for making sure that it runs only
     * once per View instance.
     * @return void
     */
    private function _loadCustomThemeScripts()
    {
        if ($this->_customScriptsLoaded) {
            return;
        }

        $pluginBroker = get_plugin_broker();
        if ($pluginBroker) {
            $tmpPluginDir = $pluginBroker->getCurrentPluginDirName();
            $newPluginDir = $pluginBroker->setCurrentPluginDirName(
                self::THEME_HOOK_NAMESPACE);
        }
        foreach ($this->getScriptPaths() as $path) {
            $customScriptPath = $path . 'custom.php';
            if (file_exists($customScriptPath)) {
                include $customScriptPath;
            }
        }
        if ($pluginBroker) {
            $pluginBroker->setCurrentPluginDirName($tmpPluginDir);
        }
        $this->_customScriptsLoaded = true;
    }
    
    /**
     * Add a script path to the view.
     * 
     * @internal Overrides Zend_View_Abstract to ensure that duplicate paths
     * are not added to the stack.  Fixes a bug where include'ing the same 
     * script twice causes fatal errors.
     * @param string $path Local filesystem path.
     */
    public function addScriptPath($path)
    {
        // For some unknown reason, Zend_View adds a trailing slash to paths.
        // Need to add that for the purposes of comparison.
        $path = rtrim($path, '/') . '/';
        
        if (!in_array($path, $this->getScriptPaths())) {
            return parent::addScriptPath($path);
        }
    }
}
```

<h2>vardump(get_view())</h2>
<pre class="xdebug-var-dump" dir="ltr"><small>C:\Program Files (x86)\Ampps\www\lowell\themes\maplowell\neatline\exhibits\themes\following-the-money\show.php:25:</small>
<b>object</b>(<i>Omeka_View</i>)[<i>89</i>]
  <i>protected</i> '_asset_paths' <font color="#888a85">=&gt;</font> 
    <b>array</b> <i>(size=10)</i>
      0 <font color="#888a85">=&gt;</font> 
        <b>array</b> <i>(size=2)</i>
          0 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'C:\Program Files (x86)\Ampps\www\lowell/themes/maplowell/neatline'</font> <i>(length=65)</i>
          1 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'http://localhost/lowell/themes/maplowell/neatline'</font> <i>(length=49)</i>
      1 <font color="#888a85">=&gt;</font> 
        <b>array</b> <i>(size=2)</i>
          0 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'C:\Program Files (x86)\Ampps\www\lowell/plugins/Neatline/views/public'</font> <i>(length=69)</i>
          1 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'http://localhost/lowell/plugins/Neatline/views/public'</font> <i>(length=53)</i>
      2 <font color="#888a85">=&gt;</font> 
        <b>array</b> <i>(size=2)</i>
          0 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'C:\Program Files (x86)\Ampps\www\lowell/plugins/Neatline/views/shared'</font> <i>(length=69)</i>
          1 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'http://localhost/lowell/plugins/Neatline/views/shared'</font> <i>(length=53)</i>
      3 <font color="#888a85">=&gt;</font> 
        <b>array</b> <i>(size=2)</i>
          0 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'C:\Program Files (x86)\Ampps\www\lowell/themes/maplowell'</font> <i>(length=56)</i>
          1 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'http://localhost/lowell/themes/maplowell'</font> <i>(length=40)</i>
      4 <font color="#888a85">=&gt;</font> 
        <b>array</b> <i>(size=2)</i>
          0 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'C:\Program Files (x86)\Ampps\www\lowell/application/views/scripts'</font> <i>(length=65)</i>
          1 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'http://localhost/lowell/application/views/scripts'</font> <i>(length=49)</i>
      5 <font color="#888a85">=&gt;</font> 
        <b>array</b> <i>(size=2)</i>
          0 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'C:\Program Files (x86)\Ampps\www\lowell/plugins/SimplePages/views/public'</font> <i>(length=72)</i>
          1 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'http://localhost/lowell/plugins/SimplePages/views/public'</font> <i>(length=56)</i>
      6 <font color="#888a85">=&gt;</font> 
        <b>array</b> <i>(size=2)</i>
          0 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'C:\Program Files (x86)\Ampps\www\lowell/plugins/NeatlineWaypoints/views/shared'</font> <i>(length=78)</i>
          1 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'http://localhost/lowell/plugins/NeatlineWaypoints/views/shared'</font> <i>(length=62)</i>
      7 <font color="#888a85">=&gt;</font> 
        <b>array</b> <i>(size=2)</i>
          0 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'C:\Program Files (x86)\Ampps\www\lowell/plugins/NeatlineText/views/shared'</font> <i>(length=73)</i>
          1 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'http://localhost/lowell/plugins/NeatlineText/views/shared'</font> <i>(length=57)</i>
      8 <font color="#888a85">=&gt;</font> 
        <b>array</b> <i>(size=2)</i>
          0 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'C:\Program Files (x86)\Ampps\www\lowell/plugins/NeatlineSimile/views/shared'</font> <i>(length=75)</i>
          1 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'http://localhost/lowell/plugins/NeatlineSimile/views/shared'</font> <i>(length=59)</i>
      9 <font color="#888a85">=&gt;</font> 
        <b>array</b> <i>(size=2)</i>
          0 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'C:\Program Files (x86)\Ampps\www\lowell/plugins/NeatlineFeatures/views/shared'</font> <i>(length=77)</i>
          1 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'http://localhost/lowell/plugins/NeatlineFeatures/views/shared'</font> <i>(length=61)</i>
  <i>private</i> '_customScriptsLoaded' <font color="#888a85">=&gt;</font> <small>boolean</small> <font color="#75507b">true</font>
  <i>private</i> '_path' <small>(Zend_View_Abstract)</small> <font color="#888a85">=&gt;</font> 
    <b>array</b> <i>(size=3)</i>
      'script' <font color="#888a85">=&gt;</font> 
        <b>array</b> <i>(size=10)</i>
          0 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'C:\Program Files (x86)\Ampps\www\lowell/themes/maplowell/neatline/'</font> <i>(length=66)</i>
          1 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'C:\Program Files (x86)\Ampps\www\lowell/plugins/Neatline/views/public/'</font> <i>(length=70)</i>
          2 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'C:\Program Files (x86)\Ampps\www\lowell/plugins/Neatline/views/shared/'</font> <i>(length=70)</i>
          3 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'C:\Program Files (x86)\Ampps\www\lowell/themes/maplowell/'</font> <i>(length=57)</i>
          4 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'C:\Program Files (x86)\Ampps\www\lowell/application/views/scripts/'</font> <i>(length=66)</i>
          5 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'C:\Program Files (x86)\Ampps\www\lowell/plugins/SimplePages/views/public/'</font> <i>(length=73)</i>
          6 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'C:\Program Files (x86)\Ampps\www\lowell/plugins/NeatlineWaypoints/views/shared/'</font> <i>(length=79)</i>
          7 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'C:\Program Files (x86)\Ampps\www\lowell/plugins/NeatlineText/views/shared/'</font> <i>(length=74)</i>
          8 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'C:\Program Files (x86)\Ampps\www\lowell/plugins/NeatlineSimile/views/shared/'</font> <i>(length=76)</i>
          9 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'C:\Program Files (x86)\Ampps\www\lowell/plugins/NeatlineFeatures/views/shared/'</font> <i>(length=78)</i>
      'helper' <font color="#888a85">=&gt;</font> 
        <b>array</b> <i>(size=0)</i>
          <i><font color="#888a85">empty</font></i>
      'filter' <font color="#888a85">=&gt;</font> 
        <b>array</b> <i>(size=0)</i>
          <i><font color="#888a85">empty</font></i>
  <i>private</i> '_file' <small>(Zend_View_Abstract)</small> <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'C:\Program Files (x86)\Ampps\www\lowell/themes/maplowell/neatline/exhibits/themes/following-the-money/show.php'</font> <i>(length=110)</i>
  <i>private</i> '_helper' <small>(Zend_View_Abstract)</small> <font color="#888a85">=&gt;</font> 
    <b>array</b> <i>(size=7)</i>
      'Doctype' <font color="#888a85">=&gt;</font> 
        <b>object</b>(<i>Zend_View_Helper_Doctype</i>)[<i>92</i>]
          <i>protected</i> '_defaultDoctype' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'HTML4_LOOSE'</font> <i>(length=11)</i>
          <i>protected</i> '_registry' <font color="#888a85">=&gt;</font> 
            <b>object</b>(<i>ArrayObject</i>)[<i>93</i>]
              ...
          <i>protected</i> '_regKey' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'Zend_View_Helper_Doctype'</font> <i>(length=24)</i>
          <i>public</i> 'view' <font color="#888a85">=&gt;</font> 
            <i>&amp;</i><b>object</b>(<i>Omeka_View</i>)[<i>89</i>]
      'HeadScript' <font color="#888a85">=&gt;</font> 
        <b>object</b>(<i>Zend_View_Helper_HeadScript</i>)[<i>207</i>]
          <i>protected</i> '_regKey' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'Zend_View_Helper_HeadScript'</font> <i>(length=27)</i>
          <i>protected</i> '_arbitraryAttributes' <font color="#888a85">=&gt;</font> <small>boolean</small> <font color="#75507b">false</font>
          <i>protected</i> '_captureLock' <font color="#888a85">=&gt;</font> <font color="#3465a4">null</font>
          <i>protected</i> '_captureScriptType' <font color="#888a85">=&gt;</font> <font color="#3465a4">null</font>
          <i>protected</i> '_captureScriptAttrs' <font color="#888a85">=&gt;</font> <font color="#3465a4">null</font>
          <i>protected</i> '_captureType' <font color="#888a85">=&gt;</font> <font color="#3465a4">null</font>
          <i>protected</i> '_optionalAttributes' <font color="#888a85">=&gt;</font> 
            <b>array</b> <i>(size=4)</i>
              ...
          <i>protected</i> '_requiredAttributes' <font color="#888a85">=&gt;</font> 
            <b>array</b> <i>(size=1)</i>
              ...
          <i>public</i> 'useCdata' <font color="#888a85">=&gt;</font> <small>boolean</small> <font color="#75507b">false</font>
          <i>protected</i> '_container' <font color="#888a85">=&gt;</font> 
            <b>object</b>(<i>Zend_View_Helper_Placeholder_Container</i>)[<i>231</i>]
              ...
          <i>protected</i> '_registry' <font color="#888a85">=&gt;</font> 
            <b>object</b>(<i>Zend_View_Helper_Placeholder_Registry</i>)[<i>232</i>]
              ...
          <i>protected</i> '_autoEscape' <font color="#888a85">=&gt;</font> <small>boolean</small> <font color="#75507b">true</font>
          <i>public</i> 'view' <font color="#888a85">=&gt;</font> 
            <i>&amp;</i><b>object</b>(<i>Omeka_View</i>)[<i>89</i>]
      'HeadLink' <font color="#888a85">=&gt;</font> 
        <b>object</b>(<i>Zend_View_Helper_HeadLink</i>)[<i>227</i>]
          <i>protected</i> '_itemKeys' <font color="#888a85">=&gt;</font> 
            <b>array</b> <i>(size=11)</i>
              ...
          <i>protected</i> '_regKey' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'Zend_View_Helper_HeadLink'</font> <i>(length=25)</i>
          <i>protected</i> '_container' <font color="#888a85">=&gt;</font> 
            <b>object</b>(<i>Zend_View_Helper_Placeholder_Container</i>)[<i>226</i>]
              ...
          <i>protected</i> '_registry' <font color="#888a85">=&gt;</font> 
            <b>object</b>(<i>Zend_View_Helper_Placeholder_Registry</i>)[<i>232</i>]
              ...
          <i>protected</i> '_autoEscape' <font color="#888a85">=&gt;</font> <small>boolean</small> <font color="#75507b">true</font>
          <i>public</i> 'view' <font color="#888a85">=&gt;</font> 
            <i>&amp;</i><b>object</b>(<i>Omeka_View</i>)[<i>89</i>]
      'Partial' <font color="#888a85">=&gt;</font> 
        <b>object</b>(<i>Zend_View_Helper_Partial</i>)[<i>228</i>]
          <i>protected</i> '_objectKey' <font color="#888a85">=&gt;</font> <font color="#3465a4">null</font>
          <i>public</i> 'view' <font color="#888a85">=&gt;</font> 
            <i>&amp;</i><b>object</b>(<i>Omeka_View</i>)[<i>89</i>]
      'HeadStyle' <font color="#888a85">=&gt;</font> 
        <b>object</b>(<i>Zend_View_Helper_HeadStyle</i>)[<i>216</i>]
          <i>protected</i> '_regKey' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'Zend_View_Helper_HeadStyle'</font> <i>(length=26)</i>
          <i>protected</i> '_optionalAttributes' <font color="#888a85">=&gt;</font> 
            <b>array</b> <i>(size=4)</i>
              ...
          <i>protected</i> '_mediaTypes' <font color="#888a85">=&gt;</font> 
            <b>array</b> <i>(size=9)</i>
              ...
          <i>protected</i> '_captureAttrs' <font color="#888a85">=&gt;</font> <font color="#3465a4">null</font>
          <i>protected</i> '_captureLock' <font color="#888a85">=&gt;</font> <font color="#3465a4">null</font>
          <i>protected</i> '_captureType' <font color="#888a85">=&gt;</font> <font color="#3465a4">null</font>
          <i>protected</i> '_container' <font color="#888a85">=&gt;</font> 
            <b>object</b>(<i>Zend_View_Helper_Placeholder_Container</i>)[<i>213</i>]
              ...
          <i>protected</i> '_registry' <font color="#888a85">=&gt;</font> 
            <b>object</b>(<i>Zend_View_Helper_Placeholder_Registry</i>)[<i>232</i>]
              ...
          <i>protected</i> '_autoEscape' <font color="#888a85">=&gt;</font> <small>boolean</small> <font color="#75507b">true</font>
          <i>public</i> 'view' <font color="#888a85">=&gt;</font> 
            <i>&amp;</i><b>object</b>(<i>Omeka_View</i>)[<i>89</i>]
      'Navigation' <font color="#888a85">=&gt;</font> 
        <b>object</b>(<i>Zend_View_Helper_Navigation</i>)[<i>272</i>]
          <i>protected</i> '_defaultProxy' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'menu'</font> <i>(length=4)</i>
          <i>protected</i> '_helpers' <font color="#888a85">=&gt;</font> 
            <b>array</b> <i>(size=1)</i>
              ...
          <i>protected</i> '_injectContainer' <font color="#888a85">=&gt;</font> <small>boolean</small> <font color="#75507b">true</font>
          <i>protected</i> '_injectAcl' <font color="#888a85">=&gt;</font> <small>boolean</small> <font color="#75507b">true</font>
          <i>protected</i> '_injectTranslator' <font color="#888a85">=&gt;</font> <small>boolean</small> <font color="#75507b">true</font>
          <i>protected</i> '_container' <font color="#888a85">=&gt;</font> 
            <b>object</b>(<i>Zend_Navigation</i>)[<i>275</i>]
              ...
          <i>protected</i> '_minDepth' <font color="#888a85">=&gt;</font> <font color="#3465a4">null</font>
          <i>protected</i> '_maxDepth' <font color="#888a85">=&gt;</font> <font color="#3465a4">null</font>
          <i>protected</i> '_indent' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">''</font> <i>(length=0)</i>
          <i>protected</i> '_formatOutput' <font color="#888a85">=&gt;</font> <small>boolean</small> <font color="#75507b">true</font>
          <i>protected</i> '_prefixForId' <font color="#888a85">=&gt;</font> <font color="#3465a4">null</font>
          <i>protected</i> '_skipPrefixForId' <font color="#888a85">=&gt;</font> <small>boolean</small> <font color="#75507b">false</font>
          <i>protected</i> '_translator' <font color="#888a85">=&gt;</font> <font color="#3465a4">null</font>
          <i>protected</i> '_acl' <font color="#888a85">=&gt;</font> <font color="#3465a4">null</font>
          <i>protected</i> '_renderInvisible' <font color="#888a85">=&gt;</font> <small>boolean</small> <font color="#75507b">false</font>
          <i>protected</i> '_role' <font color="#888a85">=&gt;</font> <font color="#3465a4">null</font>
          <i>protected</i> '_useTranslator' <font color="#888a85">=&gt;</font> <small>boolean</small> <font color="#75507b">true</font>
          <i>protected</i> '_useAcl' <font color="#888a85">=&gt;</font> <small>boolean</small> <font color="#75507b">true</font>
          <i>protected</i> '_closingBracket' <font color="#888a85">=&gt;</font> <font color="#3465a4">null</font>
          <i>public</i> 'view' <font color="#888a85">=&gt;</font> 
            <i>&amp;</i><b>object</b>(<i>Omeka_View</i>)[<i>89</i>]
      'Menu' <font color="#888a85">=&gt;</font> 
        <b>object</b>(<i>Zend_View_Helper_Navigation_Menu</i>)[<i>276</i>]
          <i>protected</i> '_ulClass' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'navigation'</font> <i>(length=10)</i>
          <i>protected</i> '_ulId' <font color="#888a85">=&gt;</font> <font color="#3465a4">null</font>
          <i>protected</i> '_activeClass' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'active'</font> <i>(length=6)</i>
          <i>protected</i> '_parentClass' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'menu-parent'</font> <i>(length=11)</i>
          <i>protected</i> '_renderParentClass' <font color="#888a85">=&gt;</font> <small>boolean</small> <font color="#75507b">false</font>
          <i>protected</i> '_onlyActiveBranch' <font color="#888a85">=&gt;</font> <small>boolean</small> <font color="#75507b">false</font>
          <i>protected</i> '_renderParents' <font color="#888a85">=&gt;</font> <small>boolean</small> <font color="#75507b">true</font>
          <i>protected</i> '_partial' <font color="#888a85">=&gt;</font> <font color="#3465a4">null</font>
          <i>protected</i> '_expandSiblingNodesOfActiveBranch' <font color="#888a85">=&gt;</font> <small>boolean</small> <font color="#75507b">false</font>
          <i>protected</i> '_addPageClassToLi' <font color="#888a85">=&gt;</font> <small>boolean</small> <font color="#75507b">false</font>
          <i>protected</i> '_innerIndent' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'    '</font> <i>(length=4)</i>
          <i>protected</i> '_container' <font color="#888a85">=&gt;</font> 
            <b>object</b>(<i>Omeka_Navigation</i>)[<i>274</i>]
              ...
          <i>protected</i> '_minDepth' <font color="#888a85">=&gt;</font> <font color="#3465a4">null</font>
          <i>protected</i> '_maxDepth' <font color="#888a85">=&gt;</font> <font color="#3465a4">null</font>
          <i>protected</i> '_indent' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">''</font> <i>(length=0)</i>
          <i>protected</i> '_formatOutput' <font color="#888a85">=&gt;</font> <small>boolean</small> <font color="#75507b">true</font>
          <i>protected</i> '_prefixForId' <font color="#888a85">=&gt;</font> <font color="#3465a4">null</font>
          <i>protected</i> '_skipPrefixForId' <font color="#888a85">=&gt;</font> <small>boolean</small> <font color="#75507b">false</font>
          <i>protected</i> '_translator' <font color="#888a85">=&gt;</font> <font color="#3465a4">null</font>
          <i>protected</i> '_acl' <font color="#888a85">=&gt;</font> <font color="#3465a4">null</font>
          <i>protected</i> '_renderInvisible' <font color="#888a85">=&gt;</font> <small>boolean</small> <font color="#75507b">false</font>
          <i>protected</i> '_role' <font color="#888a85">=&gt;</font> <font color="#3465a4">null</font>
          <i>protected</i> '_useTranslator' <font color="#888a85">=&gt;</font> <small>boolean</small> <font color="#75507b">true</font>
          <i>protected</i> '_useAcl' <font color="#888a85">=&gt;</font> <small>boolean</small> <font color="#75507b">true</font>
          <i>protected</i> '_closingBracket' <font color="#888a85">=&gt;</font> <font color="#3465a4">null</font>
          <i>public</i> 'view' <font color="#888a85">=&gt;</font> 
            <i>&amp;</i><b>object</b>(<i>Omeka_View</i>)[<i>89</i>]
  <i>private</i> '_helperLoaded' <small>(Zend_View_Abstract)</small> <font color="#888a85">=&gt;</font> 
    <b>array</b> <i>(size=0)</i>
      <i><font color="#888a85">empty</font></i>
  <i>private</i> '_helperLoadedDir' <small>(Zend_View_Abstract)</small> <font color="#888a85">=&gt;</font> 
    <b>array</b> <i>(size=0)</i>
      <i><font color="#888a85">empty</font></i>
  <i>private</i> '_filter' <small>(Zend_View_Abstract)</small> <font color="#888a85">=&gt;</font> 
    <b>array</b> <i>(size=0)</i>
      <i><font color="#888a85">empty</font></i>
  <i>private</i> '_filterClass' <small>(Zend_View_Abstract)</small> <font color="#888a85">=&gt;</font> 
    <b>array</b> <i>(size=0)</i>
      <i><font color="#888a85">empty</font></i>
  <i>private</i> '_filterLoaded' <small>(Zend_View_Abstract)</small> <font color="#888a85">=&gt;</font> 
    <b>array</b> <i>(size=0)</i>
      <i><font color="#888a85">empty</font></i>
  <i>private</i> '_filterLoadedDir' <small>(Zend_View_Abstract)</small> <font color="#888a85">=&gt;</font> 
    <b>array</b> <i>(size=0)</i>
      <i><font color="#888a85">empty</font></i>
  <i>private</i> '_escape' <small>(Zend_View_Abstract)</small> <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'htmlspecialchars'</font> <i>(length=16)</i>
  <i>private</i> '_encoding' <small>(Zend_View_Abstract)</small> <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'UTF-8'</font> <i>(length=5)</i>
  <i>private</i> '_lfiProtectionOn' <small>(Zend_View_Abstract)</small> <font color="#888a85">=&gt;</font> <small>boolean</small> <font color="#75507b">true</font>
  <i>private</i> '_loaders' <small>(Zend_View_Abstract)</small> <font color="#888a85">=&gt;</font> 
    <b>array</b> <i>(size=2)</i>
      'filter' <font color="#888a85">=&gt;</font> 
        <b>object</b>(<i>Zend_Loader_PluginLoader</i>)[<i>90</i>]
          <i>protected</i> '_loadedPluginPaths' <font color="#888a85">=&gt;</font> 
            <b>array</b> <i>(size=0)</i>
              ...
          <i>protected</i> '_loadedPlugins' <font color="#888a85">=&gt;</font> 
            <b>array</b> <i>(size=0)</i>
              ...
          <i>protected</i> '_prefixToPaths' <font color="#888a85">=&gt;</font> 
            <b>array</b> <i>(size=1)</i>
              ...
          <i>protected</i> '_useStaticRegistry' <font color="#888a85">=&gt;</font> <font color="#3465a4">null</font>
      'helper' <font color="#888a85">=&gt;</font> 
        <b>object</b>(<i>Zend_Loader_PluginLoader</i>)[<i>277</i>]
          <i>protected</i> '_loadedPluginPaths' <font color="#888a85">=&gt;</font> 
            <b>array</b> <i>(size=0)</i>
              ...
          <i>protected</i> '_loadedPlugins' <font color="#888a85">=&gt;</font> 
            <b>array</b> <i>(size=1)</i>
              ...
          <i>protected</i> '_prefixToPaths' <font color="#888a85">=&gt;</font> 
            <b>array</b> <i>(size=3)</i>
              ...
          <i>protected</i> '_useStaticRegistry' <font color="#888a85">=&gt;</font> <font color="#3465a4">null</font>
  <i>private</i> '_loaderTypes' <small>(Zend_View_Abstract)</small> <font color="#888a85">=&gt;</font> 
    <b>array</b> <i>(size=2)</i>
      0 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'filter'</font> <i>(length=6)</i>
      1 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'helper'</font> <i>(length=6)</i>
  <i>private</i> '_strictVars' <small>(Zend_View_Abstract)</small> <font color="#888a85">=&gt;</font> <small>boolean</small> <font color="#75507b">false</font>
  <i>public</i> 'neatline_exhibit' <font color="#888a85">=&gt;</font> 
    <b>object</b>(<i>NeatlineExhibit</i>)[<i>235</i>]
      <i>public</i> 'owner_id' <font color="#888a85">=&gt;</font> <small>int</small> <font color="#4e9a06">1</font>
      <i>public</i> 'added' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'2017-06-14 15:53:30'</font> <i>(length=19)</i>
      <i>public</i> 'modified' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'2018-04-01 04:41:16'</font> <i>(length=19)</i>
      <i>public</i> 'published' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'2017-06-15 02:32:42'</font> <i>(length=19)</i>
      <i>public</i> 'item_query' <font color="#888a85">=&gt;</font> <font color="#3465a4">null</font>
      <i>public</i> 'spatial_layers' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'OpenStreetMap'</font> <i>(length=13)</i>
      <i>public</i> 'spatial_layer' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'OpenStreetMap'</font> <i>(length=13)</i>
      <i>public</i> 'image_layer' <font color="#888a85">=&gt;</font> <font color="#3465a4">null</font>
      <i>public</i> 'image_height' <font color="#888a85">=&gt;</font> <font color="#3465a4">null</font>
      <i>public</i> 'image_width' <font color="#888a85">=&gt;</font> <font color="#3465a4">null</font>
      <i>public</i> 'zoom_levels' <font color="#888a85">=&gt;</font> <small>int</small> <font color="#4e9a06">20</font>
      <i>public</i> 'wms_address' <font color="#888a85">=&gt;</font> <font color="#3465a4">null</font>
      <i>public</i> 'wms_layers' <font color="#888a85">=&gt;</font> <font color="#3465a4">null</font>
      <i>public</i> 'widgets' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'Text,Waypoints'</font> <i>(length=14)</i>
      <i>public</i> 'title' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'Following the Money: How Lowell Made Boston, 1850'</font> <i>(length=49)</i>
      <i>public</i> 'slug' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'following-the-money'</font> <i>(length=19)</i>
      <i>public</i> 'narrative' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'&lt;h3&gt;&lt;strong&gt;Introduction: Lowell and Boston&lt;/strong&gt;&lt;/h3&gt;

&lt;p&gt;Although nearly thirty miles apart, Boston wouldn&amp;#39;t be Boston without Lowell.&lt;/p&gt;

&lt;p&gt;This map takes that idea as its most general claim. It has a number of different ramifications. You can read the following introduction to get a better idea of how the map works &amp;mdash; or you can just dive in my clicking on highlighted buildings/arrows and&amp;nbsp;moving around the map. When you click on a building or arrow, you access further images, text'...</font> <i>(length=15356)</i>
      <i>public</i> 'spatial_querying' <font color="#888a85">=&gt;</font> <small>int</small> <font color="#4e9a06">1</font>
      <i>public</i> 'public' <font color="#888a85">=&gt;</font> <small>int</small> <font color="#4e9a06">1</font>
      <i>public</i> 'styles' <font color="#888a85">=&gt;</font> <font color="#3465a4">null</font>
      <i>public</i> 'map_focus' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'-7924773.7237044,5226897.7582125'</font> <i>(length=32)</i>
      <i>public</i> 'map_zoom' <font color="#888a85">=&gt;</font> <small>int</small> <font color="#4e9a06">10</font>
      <i>private</i> 'expansions' <small>(Neatline_Row_Expandable)</small> <font color="#888a85">=&gt;</font> 
        <b>array</b> <i>(size=6)</i>
          'simile_default_date' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'1840'</font> <i>(length=4)</i>
          'simile_interval_unit' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'YEAR'</font> <i>(length=4)</i>
          'simile_interval_pixels' <font color="#888a85">=&gt;</font> <small>int</small> <font color="#4e9a06">100</font>
          'simile_tape_height' <font color="#888a85">=&gt;</font> <small>int</small> <font color="#4e9a06">10</font>
          'simile_track_height' <font color="#888a85">=&gt;</font> <small>int</small> <font color="#4e9a06">10</font>
          'parent_id' <font color="#888a85">=&gt;</font> <small>int</small> <font color="#4e9a06">1</font>
      <i>public</i> 'id' <font color="#888a85">=&gt;</font> <small>int</small> <font color="#4e9a06">1</font>
      <i>private</i> '_errors' <small>(Omeka_Record_AbstractRecord)</small> <font color="#888a85">=&gt;</font> 
        <b>object</b>(<i>Omeka_Validate_Errors</i>)[<i>234</i>]
          <i>protected</i> '_errors' <font color="#888a85">=&gt;</font> 
            <b>array</b> <i>(size=0)</i>
              ...
          <i>private</i> 'storage' <small>(ArrayObject)</small> <font color="#888a85">=&gt;</font> 
            <b>array</b> <i>(size=0)</i>
              ...
      <i>protected</i> '_cache' <font color="#888a85">=&gt;</font> 
        <b>array</b> <i>(size=0)</i>
          <i><font color="#888a85">empty</font></i>
      <i>protected</i> '_mixins' <font color="#888a85">=&gt;</font> 
        <b>array</b> <i>(size=1)</i>
          0 <font color="#888a85">=&gt;</font> 
            <b>object</b>(<i>Mixin_Owner</i>)[<i>233</i>]
              ...
      <i>protected</i> '_db' <font color="#888a85">=&gt;</font> 
        <b>object</b>(<i>Omeka_Db</i>)[<i>53</i>]
          <i>public</i> 'prefix' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'omek_'</font> <i>(length=5)</i>
          <i>protected</i> '_adapter' <font color="#888a85">=&gt;</font> 
            <b>object</b>(<i>Zend_Db_Adapter_Mysqli</i>)[<i>51</i>]
              ...
          <i>protected</i> '_tables' <font color="#888a85">=&gt;</font> 
            <b>array</b> <i>(size=6)</i>
              ...
          <i>private</i> '_logger' <font color="#888a85">=&gt;</font> <font color="#3465a4">null</font>
      <i>protected</i> '_related' <font color="#888a85">=&gt;</font> 
        <b>array</b> <i>(size=0)</i>
          <i><font color="#888a85">empty</font></i>
      <i>protected</i> '_postData' <font color="#888a85">=&gt;</font> <font color="#3465a4">null</font>
      <i>private</i> '_locked' <small>(Omeka_Record_AbstractRecord)</small> <font color="#888a85">=&gt;</font> <small>boolean</small> <font color="#75507b">false</font>
      <i>private</i> '_eventCallbacks' <small>(Omeka_Record_AbstractRecord)</small> <font color="#888a85">=&gt;</font> 
        <b>array</b> <i>(size=4)</i>
          0 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'beforeSave'</font> <i>(length=10)</i>
          1 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'afterSave'</font> <i>(length=9)</i>
          2 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'beforeDelete'</font> <i>(length=12)</i>
          3 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'afterDelete'</font> <i>(length=11)</i>
      <i>private</i> '_pluginBroker' <small>(Omeka_Record_AbstractRecord)</small> <font color="#888a85">=&gt;</font> <font color="#3465a4">null</font>
</pre>

## Zend View.php abstract:

```php
<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_View
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */


/**
 * Abstract master class for extension.
 */
require_once 'Zend/View/Abstract.php';


/**
 * Concrete class for handling view scripts.
 *
 * @category  Zend
 * @package   Zend_View
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd     New BSD License
 *
 * Convenience methods for build in helpers (@see __call):
 *
 * @method string baseUrl($file = null)
 * @method string currency($value = null, $currency = null)
 * @method Zend_View_Helper_Cycle cycle(array $data = array(), $name = Zend_View_Helper_Cycle::DEFAULT_NAME)
 * @method Zend_View_Helper_Doctype doctype($doctype = null)
 * @method string fieldset($name, $content, $attribs = null)
 * @method string form($name, $attribs = null, $content = false)
 * @method string formButton($name, $value = null, $attribs = null)
 * @method string formCheckbox($name, $value = null, $attribs = null, array $checkedOptions = null)
 * @method string formErrors($errors, array $options = null)
 * @method string formFile($name, $attribs = null)
 * @method string formHidden($name, $value = null, array $attribs = null)
 * @method string formImage($name, $value = null, $attribs = null)
 * @method string formLabel($name, $value = null, array $attribs = null)
 * @method string formMultiCheckbox($name, $value = null, $attribs = null, $options = null, $listsep = "<br />\n")
 * @method string formNote($name, $value = null)
 * @method string formPassword($name, $value = null, $attribs = null)
 * @method string formRadio($name, $value = null, $attribs = null, $options = null, $listsep = "<br />\n")
 * @method string formReset($name = '', $value = 'Reset', $attribs = null)
 * @method string formSelect($name, $value = null, $attribs = null, $options = null, $listsep = "<br />\n")
 * @method string formSubmit($name, $value = null, $attribs = null)
 * @method string formText($name, $value = null, $attribs = null)
 * @method string formTextarea($name, $value = null, $attribs = null)
 * @method Zend_View_Helper_Gravatar gravatar($email = "", $options = array(), $attribs = array())
 * @method Zend_View_Helper_HeadLink headLink(array $attributes = null, $placement = Zend_View_Helper_Placeholder_Container_Abstract::APPEND)
 * @method Zend_View_Helper_HeadMeta headMeta($content = null, $keyValue = null, $keyType = 'name', $modifiers = array(), $placement = Zend_View_Helper_Placeholder_Container_Abstract::APPEND)
 * @method Zend_View_Helper_HeadScript headScript($mode = Zend_View_Helper_HeadScript::FILE, $spec = null, $placement = 'APPEND', array $attrs = array(), $type = 'text/javascript')
 * @method Zend_View_Helper_HeadStyle headStyle($content = null, $placement = 'APPEND', $attributes = array())
 * @method Zend_View_Helper_HeadTitle headTitle($title = null, $setType = null)
 * @method string htmlFlash($data, array $attribs = array(), array $params = array(), $content = null)
 * @method string htmlList(array $items, $ordered = false, $attribs = false, $escape = true)
 * @method string htmlObject($data, $type, array $attribs = array(), array $params = array(), $content = null)
 * @method string htmlPage($data, array $attribs = array(), array $params = array(), $content = null)
 * @method string htmlQuicktime($data, array $attribs = array(), array $params = array(), $content = null)
 * @method Zend_View_Helper_InlineScript inlineScript($mode = Zend_View_Helper_HeadScript::FILE, $spec = null, $placement = 'APPEND', array $attrs = array(), $type = 'text/javascript')
 * @method string|void json($data, $keepLayouts = false, $encodeData = true)
 * @method Zend_View_Helper_Layout layout()
 * @method Zend_View_Helper_Navigation navigation(Zend_Navigation_Container $container = null)
 * @method string paginationControl(Zend_Paginator $paginator = null, $scrollingStyle = null, $partial = null, $params = null)
 * @method string partial($name = null, $module = null, $model = null)
 * @method string partialLoop($name = null, $module = null, $model = null)
 * @method Zend_View_Helper_Placeholder_Container_Abstract placeholder($name)
 * @method void renderToPlaceholder($script, $placeholder)
 * @method string serverUrl($requestUri = null)
 * @method string translate($messageid = null)
 * @method string url(array $urlOptions = array(), $name = null, $reset = false, $encode = true)
 * @method Zend_Http_UserAgent userAgent(Zend_Http_UserAgent $userAgent = null)
 */
class Zend_View extends Zend_View_Abstract
{
    /**
     * Whether or not to use streams to mimic short tags
     * @var bool
     */
    private $_useViewStream = false;

    /**
     * Whether or not to use stream wrapper if short_open_tag is false
     * @var bool
     */
    private $_useStreamWrapper = false;

    /**
     * Constructor
     *
     * Register Zend_View_Stream stream wrapper if short tags are disabled.
     *
     * @param  array $config
     * @return void
     */
    public function __construct($config = array())
    {
        $this->_useViewStream = (bool) ini_get('short_open_tag') ? false : true;
        if ($this->_useViewStream) {
            if (!in_array('zend.view', stream_get_wrappers())) {
                require_once 'Zend/View/Stream.php';
                stream_wrapper_register('zend.view', 'Zend_View_Stream');
            }
        }

        if (array_key_exists('useStreamWrapper', $config)) {
            $this->setUseStreamWrapper($config['useStreamWrapper']);
        }

        parent::__construct($config);
    }

    /**
     * Set flag indicating if stream wrapper should be used if short_open_tag is off
     *
     * @param  bool $flag
     * @return Zend_View
     */
    public function setUseStreamWrapper($flag)
    {
        $this->_useStreamWrapper = (bool) $flag;
        return $this;
    }

    /**
     * Should the stream wrapper be used if short_open_tag is off?
     *
     * @return bool
     */
    public function useStreamWrapper()
    {
        return $this->_useStreamWrapper;
    }

    /**
     * Includes the view script in a scope with only public $this variables.
     *
     * @param string The view script to execute.
     */
    protected function _run()
    {
        if ($this->_useViewStream && $this->useStreamWrapper()) {
            include 'zend.view://' . func_get_arg(0);
        } else {
            include func_get_arg(0);
        }
    }
}
```