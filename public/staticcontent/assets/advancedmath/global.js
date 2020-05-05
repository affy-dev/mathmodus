function phantom(){
	MathJax.Hub.Register.StartupHook("AsciiMath Jax Config",function () {
	  var AM = MathJax.InputJax.AsciiMath.AM;
	  AM.symbols.push(
	    {input:"phantom", tag:"mphantom", output:"phantom", tex:null, ttype:AM.TOKEN.UNARY}
	  );
	});
};