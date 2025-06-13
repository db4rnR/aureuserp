# Query Architecture (Dark Mode)

```mermaid
%%{init: {'theme': 'dark', 'themeVariables': { 'primaryColor': '#2a2a2a', 'primaryTextColor': '#ffffff', 'primaryBorderColor': '#555555', 'lineColor': '#999999', 'secondaryColor': '#252525', 'tertiaryColor': '#333333' }}}%%
classDiagram
    class Query {
        +validate()
        +rules()
    }
    
    class QueryHandler {
        +handle(Query query)
    }
    
    class ReadModel {
        +id
        +attributes
        +find()
        +findAll()
    }
    
    class QueryResult {
        +data
        +meta
    }
    
    Query --> QueryHandler: processed by
    QueryHandler --> ReadModel: retrieves from
    ReadModel --> QueryResult: transformed to
```
