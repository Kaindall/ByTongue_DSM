<h1>ByTongue_DSM</h1>
<p>Coming soon... (descrição do projeto) </p>



<hr>
<h2>Commit</h2>

<b>Modelo de Commit:</b>
	<ul><li>[Tipo]: [Verbo] descrição</li></ul>
Ex:
	<ul><li>git commit -m 'FEAT: adicionar mock de dados'</li><li>Em plataformas de auxilio visual como Github Desktop, colocar no título/header a mensagem entre aspas</li></ul>
	
<b>Tipos de commit:</b>
	<ul><li>FEAT: nova funcionalidade</li>
	<li>FIX: correção de funcionalidades/bugs</li>
	<li>REFACTOR: alteração de uma funcionalidade existente, mas sem alterar seu comportamento final</li></ul>

<hr>

<h2>Regras de Branch:</h2>
	<p>Não commitar direto na master, criar uma branch com o código do card no Jira
	<br />Ex: </br />  - INT19</p>
	<p>A branch develop é a branch de testes que deve ser um espelho da master. Diferenciando-se da master apenas no período de testes para subir pra master.</p>
	Dicas:
		<p> - Commitar constantemente a cada pequena funcionalidade implementada, mesmo que não dê merge na develop/master, assim facilita a análise e possíveis rollbacks sem perder código</p>
		
<hr>

<h2>Estrutura de Pastas:</h2>

	/: Raíz será feita para armazenar configurações e funcionalidades que não tem a ver com a aplicação (geralmente configurações de ambiente, chaves/secrets, conexões com BDs, etc)
	/src: A aplicação em si;
 	
	/src/resources/cookies: Local de armazenamento de cookies, não deve importado no github
	/src/resources/static: Conteúdo estático consumido pela aplicação como imagens, logotipos, fontes, ícones, etc
	/src/resources/style: arquivos CSS, 
		/_global: Deverá ser importado em todas as páginas (_global ex: fontes, variáveis e resets css)
		/public: CSS aplicado nas páginas deslogadas, criar ou não uma pasta para cada página fica à critério dos devs
		/trusted: CSS aplicado nas páginas/componentes logados, por exemplo, página de perfil, de compras (de pacotes de voz, se existente)
			Ideias (que talvez nunca sejam implementados): /src/resources/logs e /src/resource/data (para mocks de dados)

	==============================
 
	/src/main: Código da aplicação
	/src/main/application: Camada externa da aplicação
	/src/main/domain: Camada interna da aplicação, regras de negócio. Não deve consumir/implementar nada da camada externa, mas sim fornecer interfaces para serem consumidas.

	==============================
 
	/src/main/application/web/view: Páginas da aplicação para visualização do usuário final (HTML e JS que mudam apenas o layout)
	/src/main/application/web/client: Códigos JS com lógica exclusiva de comunicação HTTP, principalmente com o backend/controllers (ex: envios de formulários)

	=============================
 
	/src/main/application/api/client: Implementação da comunicação das regras de negócio com APIs externas no polo ativo (fazendo as chamadas). 
		As interfaces que serão implementadas nesta camada devem estar no /domain
	/src/main/application/api/controller: Orquestrador de chamadas para retornar as respostas ao usuário (polo passivo, recebendo chamadas)
	/src/main/application/api/exception: Respostas de erros retornados ao consumidor
	
 	==============================
  
	/src/main/application/router: (em progresso) Orquestrador de requisições para saber QUEM será responsável/capaz de respondê-las
	
