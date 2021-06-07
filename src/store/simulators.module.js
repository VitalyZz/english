export default {
    namespaced: 'simulators',
    state: {
        titlesOfSimulators: [
            {id: 'word-translation', name: 'Слово - перевод', component: 'WordTranslate'},
            {id: 'translation-word', name: 'Перевод - слово', component: 'TranslateWord'},
            {id: 'word-matching', name: 'Сопоставление слов', component: 'WordMatching'},
            {id: 'write-word', name: 'Написание слова', component: 'WriteWord'},
            {id: 'write-the-word-in-context', name: 'Вписать слово по контексту', component: 'WriteTheWordInContext'}
        ],
    },
    getters: {
        getTitlesOfSimulators: state => state.titlesOfSimulators
    },
}