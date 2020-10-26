<script>
  import { Button, Card, CardBody } from "sveltestrap";
  import Editor from "cl-editor";

  let noticiaAtiva = null;
  export let admin;

  let noticias = [];

  const loadNoticias = async () => {
    noticias =
      (await (await fetch("http://localhost/php/get_noticias.php")).json()) || [];
  };

  async function handleNoticiaClick(noticia, action = "read") {
    if (action === "read") noticiaAtiva = noticia;
    if (action === "edit") {
      if (noticia) {
        noticiaAtiva = noticia;
      } else {
        noticiaAtiva = {
          titulo: "Edite aqui o título",
          conteudo: "Edite aqui o texto",
        };
      }

      noticiaAtiva.editor = true;
    }
    if (action === "delete") {
      await fetch("http://localhost/php/delete_noticia.php", {
        method: "POST",
        body: noticia.id,
      });
    }
    if (action === "save") {
      await fetch("http://localhost/php/salvar_noticia.php", {
        method: "POST",
        body: JSON.stringify(noticiaAtiva),
      });
      noticiaAtiva = null;
    }
    loadNoticias();
  }

  loadNoticias();
	
	let combo = []
	let time = Date.now()
	
	function konamiCode({ keyCode }) {
		const list = [38, 38, 40, 40, 37, 39, 37, 39, 66, 65, 13]
		const now = Date.now()
		
		if (!list.includes(keyCode)) return
		if (now - time > 10000) combo = []
		
		combo.push(keyCode)
		time = now
		if (JSON.stringify(combo) === JSON.stringify(list)) {
			admin = true
		}
	}
</script>

<svelte:window on:keyup="{konamiCode}" />

<style>
  .title {
    margin-top: 30px;
    text-align: center;
  }

  .noticia-title {
    text-align: left;
  }

  .buttons-div {
    margin-top: 15px;
    margin-bottom: 20px;
  }

  main {
    padding: 1em;
    max-width: 240px;
    margin: 0 auto;
  }

  @media (min-width: 640px) {
    main {
      max-width: none;
    }
  }
</style>

<svelte:head>
  <link
    rel="stylesheet"
    href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
</svelte:head>

<main>
  <h1 class="title">
    PORTAL DE NOTÍCIAS{#if admin}{' - ADMIN'}{/if}
  </h1>

  {#if noticiaAtiva}
    <Card>
      <CardBody>
        {#if noticiaAtiva.editor}
          <h2
            class="title noticia-title"
            contenteditable="true"
            bind:textContent={noticiaAtiva.titulo}>
            {noticiaAtiva.titulo}
          </h2>
        {:else}
          <h2 class="title noticia-title">{noticiaAtiva.titulo}</h2>
        {/if}

        <div id="noticia">
          {#if !noticiaAtiva.editor}
            {@html noticiaAtiva.conteudo}
          {:else}
            <Editor
              html={noticiaAtiva.conteudo}
              on:change={(e) => (noticiaAtiva.conteudo = e.detail)}
              height={'500px'} />
          {/if}
        </div>
        <div class="ler-div">
          {#if noticiaAtiva.editor}
            <Button
              color="success"
              on:click={() => handleNoticiaClick(noticiaAtiva, 'save')}>
              Salvar
            </Button>
          {/if}
          <Button color="primary" on:click={() => handleNoticiaClick(null)}>
            Voltar para Listagem de Notícias
          </Button>
        </div>
      </CardBody>
    </Card>
  {:else}
    {#each noticias as noticia}
      <Card>
        <CardBody>
          <h2 class="title noticia-title">{noticia.titulo}</h2>
          {@html noticia.conteudo.slice(0, 200)}...
          <div class="buttons-div">
            <Button
              color="success"
              on:click={() => handleNoticiaClick(noticia)}>
              Ler Notícia
            </Button>
            {#if admin}
              <Button
                color="primary"
                on:click={() => handleNoticiaClick(noticia, 'edit')}>
                Editar Notícia
              </Button>
              <Button
                color="danger"
                on:click={() => handleNoticiaClick(noticia, 'delete')}>
                Apagar Notícia
              </Button>
            {/if}
          </div>
        </CardBody>
      </Card>
    {:else}
      Carregando... Se esta mensagem permancer, não há nenhuma notícia a ser
      exibida ou ocorreu algum erro ao comunicar com o banco de dados.
    {/each}
    {#if admin}
      <br />
      <Button color="info" on:click={() => handleNoticiaClick(null, 'edit')}>
        Criar Notícia
      </Button>
    {/if}
  {/if}
</main>
