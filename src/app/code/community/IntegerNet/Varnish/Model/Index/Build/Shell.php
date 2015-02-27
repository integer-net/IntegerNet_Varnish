<?php
/**
 * integer_net GmbH Magento Module
 *
 * @package    IntegerNet_Varnish
 * @copyright  Copyright (c) 2015 integer_net GmbH (http://www.integer-net.de/)
 * @author     integer_net GmbH <info@integer-net.de>
 * @author     Viktor Franz <vf@integer-net.de>
 */


/**
 * Class IntegerNet_Varnish_Model_Index_Build_Shell
 */
class IntegerNet_Varnish_Model_Index_Build_Shell extends IntegerNet_Varnish_Model_Index_Build
{


    /**
     * Create shell build script
     * var/integernet_varnish/build.sh
     */
    public function build()
    {
        $listUrl = Mage::getUrl('integernetvarnish/build/list', array('secret' => $this->getSecret()));
        $removeUrl = Mage::getUrl('integernetvarnish/build/remove', array('secret' => $this->getSecret()));

        $script = $this->_script($listUrl, $removeUrl, $this->_config->getBuildTimeout(), $this->_config->getBuildUserAgent());

        file_put_contents($this->_getVarDir() . 'build.sh', $script);
    }


    /**
     * @return string
     */
    public function getSecret()
    {
        $secret = (string)Mage::getConfig()->getNode('global/crypt/key') . __METHOD__;

        return hash('sha256', $secret);
    }


    /**
     *
     */
    public function writeShellBuildUrls()
    {
        $urls = $this->_indexResource->getExpiredUrls($this->_config->getBuildShellLimit());

        if ($urls) {

            $fileName = $this->_outputFileName . '.url';
            $urls = implode(PHP_EOL, $urls) . PHP_EOL;

            file_put_contents($this->_getVarDir() . $fileName, $urls);
        }
    }


    /**
     *
     */
    public function deleteJobFiles()
    {
        foreach (glob($this->_getVarDir() . 'build_*.job') as $file) {
            unlink($file);
        };

        foreach (glob($this->_getVarDir() . 'build_*.url') as $file) {
            unlink($file);
        };
    }


    /**
     * @param $url
     */
    public function removeByUrl($url)
    {
        $this->_indexResource->removeByUrl($url);
    }


    /**
     * @param string $listUrl
     * @param string $removeUrl
     * @param int $timeout
     * @param string $userAgent
     *
     * @return string Shell script
     */
    protected function _script($listUrl, $removeUrl, $timeout, $userAgent)
    {

        return <<<BUILD_SHELL_SCRIPT
#!/bin/sh

##
## integer_net Magento Module
##
## @category   IntegerNet
## @package    IntegerNet_Varnish
## @copyright  Copyright (c) 2012-2013 integer_net GmbH (http://www.integer-net.de/)
## @author     integer_net GmbH <info@integer-net.de>
## @author     Viktor Franz <vf@integer-net.de>
##

##
## change working directory to script file directory
##

cd $(dirname $0)

##
## fetch one build_*.job file in working directory
## if build_*.job file exists a build process already running
##

JOBFILE=$(find build_*.job 2>/dev/null | head -1)

if [ -n "\${JOBFILE}" ]
  then
    exit
fi

##
## fetch one build_*.url file in working directory
## if build_*.url file not exists get new url file
##

URLFILE=$(find build_*.url 2>/dev/null | head -1)

if [ -z "\${URLFILE}" ]
  then
    curl --silent --output /dev/null --url "$listUrl"
fi

##
## fetch one build_*.url file in working directory
## if build_*.url file not exists it is nothing to do
##

URLFILE=$(find build_*.url 2>/dev/null | head -1)

if [ -z "\${URLFILE}" ]
  then
    exit
fi

##
## build build_*.job and build_*.log filename based on build_*.url file
##

JOBFILE=`echo "\${URLFILE}" | sed -e 's/url$/job/'`
LOGFILE=`echo "\${URLFILE}" | sed -e 's/url$/log/'`

##
## rename build_*.url file to build_*.job to lock build script
##

mv "\${URLFILE}" "\${JOBFILE}"

##
## call for each url in build_*.job file curl command
## write log information to build_*.log file
##

while read URL
  do
    if [ -n "\${URL}" ]
      then

        STATUS=$(curl --silent --max-time {$timeout} --user-agent "{$userAgent}" --output /dev/null --write-out "$(date +\%Y-\%m-\%d\ \%H:\%M:\%S) - %{http_code} - %{time_total}s - %{url_effective}\\n" --url "\${URL}")

        echo "\${STATUS}" >> "\${LOGFILE}"

        ##
        ## remove 301 status urlsfrom index
        ##

        if [[ \${STATUS} == *"- 301 -"* ]]
        then
          curl --silent --output /dev/null --data "url=\${URL}" --url "{$removeUrl}"
        fi
    fi
done < "\${JOBFILE}"

##
## delete build_*.job file to unlock build script
##

rm "\${JOBFILE}"

BUILD_SHELL_SCRIPT;

    }
}
