## Unfixed GMP Type Confusion

Requirements: PHP &lt;= 5.6.40\
Compiled with: '--with-gmp'

Original GMP Type confusion bug was found by taoguangchen researcher and reported \[1\].
The idea of exploit is to change zval structure \[2\] of GMP object during deserialization process.
In original exploit author says about changing zval type using this code lines:
<pre class="western">	function __wakeup()
        {
            $this->ryat = 1;
        }
</pre>

![](./images/GMP_writeup_html_16a661db3f3f03db.png)

PHP supports serialization/deserialization of references. It is done using "R:" syntax. $this→ryat property is a reference to GMP object. Rewrite of $this→ryat property leads to rewrite of GMP zval.
There are many ways to rewrite zval in PHP, easies is code line like this:
<pre>$this->a = $this->b;</pre>
Part of exploit is to find this line in code of real web-application, and execute it during deserialization process.

Bug in GMP extension was "fixed" as part of delayed \_\_wakeup patch. But source code in gmp.c file was not patched. So bypassing delayed \_\_wakeup would result that this bug is still exploitable. Delayed \_\_wakeup patch was introduced in PHP 5.6.30. Generally it was a patch to prevent use-after-free bugs in unserialize. Exploits using use-after-free bugs are based on removing zval’s from memory in the middle of deserialization process and further reusing freed memory. Introduced patch suspends execution of object’s \_\_wakeup method after deserialization process finishes. It prevents removing zval’s from memory during deserialization process.

But there is another way to execute code in the middle of deserialization in PHP. In PHP there exists Serializable interface \[3\] It is for classes that implement custom serialization/deserialization methods. Deserialization of these classes can not be delayed. They have special syntax in unserialize starting with "C:". In real web-apps "unserilaize" methods are small and don’t have code lines to rewrite zval.
<pre class="western">public function unserialize($data) {
	unserialize($data);
}
</pre>
If $data is invalid serialization string (bad format), unserialize($data) call will not throw any fatal error. Deserialization process will continue after unserializing custom-serialized object. This can be used to trigger \_\_destruct method using unclosed brace in serialized $data string. Code of \_\_destruct method will be executed in the middle of unserialization process! In code of \_\_destruct method there is a big chance to find code lines that rewrite zval. The only restriction for this trick is to find a class in web-application code that implements Serializable interface.



References:

\[1\] <font color="#000080"><span lang="zxx"><u><https://bugs.php.net/bug.php?id=70513></u></span></font>\
\[2\] <font color="#000080"><span lang="zxx"><u>[https://www.phpinternalsbook.com/php5/zvals/basic\_structure.html](https://www.phpinternalsbook.com/php5/zvals/basic_structure.html)</u></span></font>\
\[3\] <font color="#000080"><span lang="zxx"><u><https://www.php.net/manual/en/class.serializable></u></span></font>