import api from '../../services/api'
import initTable from '../Table/table'
import style from './style.module.css'

const allowedType = ["text/xml"]

function showLoader(){
    document.querySelector(`.uploader-text`).classList.remove(`${style.show}`)
    document.querySelector(`.${style.loader}`).classList.add(`${style.show}`)
    
}

function hideLoader(){
    document.querySelector(`.${style.loader}`).classList.remove(`${style.show}`)
    document.querySelector(`.uploader-text`).classList.add(`${style.show}`)
}

export  function showTable(data){
    const {html, loadInputEvent} = initTable(data)
    document.querySelector(`.main`).innerHTML = html
    loadInputEvent()

}

async function requestUpload(file){
    const formData = new FormData()
    
    formData.append("file", file)
    try{
        const response = await api.post("/upload", formData, 
        {
            headers: { 
                "Content-Type": "multipart/form-data" 
            },
        })

        if(response.status !== 200){
            alert(response.data.message)
            return
        }
       
        hideLoader()
        showTable(response.data.data)

    }catch(err) {
        console.error(err.message)
        alert("Houve um erro ao fazer upload!")
    }
}


async function loadEvents(){
    const uploader = document.querySelector("input[name='uploader']")

    uploader.addEventListener('change', async function(e){
        const file = document.querySelector("input[name='uploader']").files[0]
        
        if(!allowedType.includes(file.type)){
            alert("Formato de arquivo não aceito")
            return
        }

        e.target.value = ''

        showLoader()
        await requestUpload(file)
    
    })
}

function renderHTML(){
    return `
        <section class="${style.uploaderContainer}">
            <form class="form-data" enctype="multipart/form-data">
                <input id="uploader" accept="text/xml" name="uploader" type="file" style="display:none;"/>            
                <label for="uploader" class="${style.uploaderButton}">
                    <span class="${style.loader}"></span>
                    <span class="uploader-text ${style.show}">Faça upload de seu arquivo <b>XML</b> aqui</span>
                </label>
            </form>
        </section>
    
    `
}

export default function InitUploader(){
    return {
        render: renderHTML(),
        loadEvents: () => loadEvents()
    }

}