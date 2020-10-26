<script>
  import { Router, Link, Route } from "svelte-routing";
  import Noticias from "./Noticias.svelte";

  export let url = "";
</script>

<Router url="{url}">
  <Route path="/"><Noticias /></Route>
  <Route path="/admin"><Noticias admin=true /></Route>
  <Route component={Noticias} />
</Router>