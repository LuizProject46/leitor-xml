import InitUploader from './components/Uploader/uploader'
import './style.css'

const { 
  loadEvents: loadUploaderEvents,
  render: renderUploader
} = InitUploader()

function init(){
  document.querySelector('#app').innerHTML = `
    <section class="container">
      <header class="header">
        <span>Leitor XML</span>
      </header>
      ${renderUploader}
      <main class="main">
      </main>
    
    </section>
  `

  loadUploaderEvents()
}


init()



