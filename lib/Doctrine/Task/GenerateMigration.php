<?php
/*
 *  $Id: GenerateMigration.php 2761 2007-10-07 23:42:29Z zYne $
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.doctrine-project.org>.
 */

/**
 * Doctrine_Task_GenerateMigration.
 *
 * @see        www.doctrine-project.org
 *
 * @author      Jonathan H. Wage <jwage@mac.com>
 */
class Doctrine_Task_GenerateMigration extends Doctrine_Task
{
    public $description = 'Generate new migration class definition';
    public $requiredArguments = ['class_name' => 'Name of the migration class to generate',
        'migrations_path' => 'Specify the complete path to your migration classes folder.'];
    public $optionalArguments = [];

    public function execute()
    {
        Doctrine_Core::generateMigrationClass($this->getArgument('class_name'), $this->getArgument('migrations_path'));

        $this->notify(sprintf('Generated migration class: %s successfully to %s', $this->getArgument('class_name'), $this->getArgument('migrations_path')));
    }
}
