import { showTable } from '../Uploader/uploader'
import style from './style.module.css'

let dataXML = []
let filteredData = []
let debounceInput = null
let currentInputValue = ''


function loadInputEvent(){
    const searchInput = document.querySelector("input[type='text']")

    searchInput.addEventListener('keyup', function(e){
        let searchText = e.target.value

        clearTimeout(debounceInput)
            
        debounceInput = setTimeout(() => {  
        
            currentInputValue = searchText
            if(!searchText){
                document.querySelector(`.${style.table} > tbody`).innerHTML = ''
                showTable(dataXML)
                return
            }
    
            filteredData = dataXML.filter( value => value.path.toLowerCase().includes(searchText) || value.value.toLowerCase().includes(searchText))

            if(filteredData.length == 0){
                showTable([])
                return
            }

            document.querySelector(`.${style.table} > tbody`).innerHTML = ''
            showTable(filteredData)
        }, 1000)

    })
}


function renderHTML(data){
    let htmlData = ""

    if(filteredData.length == 0 && dataXML.length > 0 && !currentInputValue){
        data = dataXML
    }

    data.forEach(element => {
        htmlData += `
            <tr>
                <td><b>${element.path}</b></td>
                <td>${element.value}</td>
            </tr>
        `
    });

    if(dataXML.length == 0){
        dataXML = data
    }
    

    if(data.length == 0){
        htmlData =  `
        <tr>
            <td>Nenhum dado encontrado :( </td>
            <td></td>
        </tr>
    `
    }

    const htmlInput = `<input placeholder="Pesquisar..." value="${currentInputValue}" type="text" class="${style.inputSearch}"/>`
    const htmlTable = `
        ${htmlInput}
        <div class="${style.tableContainer}">
            <table class="${style.table}">
                <thead>
                    <tr>
                        <th>Caminho</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    ${htmlData}
                </tbody>
            </table>
        </div>

    `

    
    
    return htmlTable
}

export default function initTable(data){
    return {
        html: renderHTML(data),
        loadInputEvent: () => loadInputEvent()
    }
}