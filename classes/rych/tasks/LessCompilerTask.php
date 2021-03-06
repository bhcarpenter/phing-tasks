<?php
/**
 * Part of the Phing tasks collection by Ryan Chouinard.
 *
 * @author Ryan Chouinard <rchouinard@gmail.com>
 * @copyright Copyright (c) 2010 Ryan Chouinard
 * @license New BSD License
 */

require_once dirname(__FILE__) . '/ProcessFilesetTask.php';

/**
 * Defines a Phing task to compile {@link http://lesscss.org LESS} syntax to
 * valid CSS.
 *
 * To use this task, include it with a taskdef tag in your build.xml file:
 *
 *     <taskdef name="lessc" classname="my.tasks.LessCompilerTask" />
 *
 * The task is now ready to be used:
 *
 *     <target name="compile-less" description="Compile LESS to CSS">
 *         <lessc targetdir="path/to/published/css">
 *             <fileset dir="path/to/less/sources">
 *                 <include name="*.less" />
 *             </fileset>
 *         </lessc>
 *     </target>
 *
 * This task makes use of {@link https://github.com/leafo/lessphp lessphp} by
 * GitHub user {@link https://github.com/leafo leafo}. The provided lessphp
 * library may differ slightly from the original Ruby version. See
 * {@link http://leafo.net/lessphp/docs/#differences the documentation} for
 * details.
 */
class LessCompilerTask extends ProcessFilesetTask
{

    /**
     * @return boolean
     */
    public function init()
    {
        $reqPath = realpath(dirname(__FILE__))
            . DIRECTORY_SEPARATOR . 'includes';
        require_once $reqPath . DIRECTORY_SEPARATOR . 'lessc.inc.php';

        return true;
    }

    /**
     * Replaces the .less extension with .css
     *
     * @param string $file
     */
    protected function _calculateTarget($file)
    {
        return new PhingFile(
            $this->_targetDir,
            str_replace('.less', '.css', $file)
        );
    }

    protected function _process($source, $target)
    {
        $lessc = new lessc($source->getAbsolutePath());
        file_put_contents(
            $target->getAbsolutePath(),
            $lessc->parse()
        );

        return true;
    }

}
