import pandas as pd
import plotly.express as px
from sklearn.cluster import AgglomerativeClustering
from sklearn.decomposition import PCA

from .data_analysis import find_closest_trees
from .data_extraction import extract_data_for_city, get_coordinates


def get_results(city, nb_trees, trees_data):
    # Vérifier d'abord si la ville existe
    lat, lon = get_coordinates(city)
    if lat is None or lon is None:
        return "Ville introuvable"

    city_data = extract_data_for_city(city)  # climat de la ville
    if not city_data:
        return "Données de la ville introuvables."
    return find_closest_trees(city_data, trees_data, nb_trees)


def clustering_trees(df : pd.DataFrame): # Page statisticator
    features = ['eau', 'sol', 'climat', 'exposition']
    X = df[features]

    # Réduction des dimensions
    pca = PCA(n_components=2)
    X_pca = pca.fit_transform(X)

    agglo = AgglomerativeClustering(
        n_clusters=3,
        linkage='complete',
        metric='euclidean'
    )
    clusters = agglo.fit_predict(X_pca)

    cluster_names = {
        0: "Gauche : chaleur & lumière",
        1: "Droite : froid & humidité",
        2: "Milieu : espèce polyvalente"
    }

    # Préparation du DataFrame
    df_pca = pd.DataFrame(X_pca, columns=['Dim1', 'Dim2'])
    df_pca['nom'] = df['genre_francais'].str.replace('_', ' ').str.title() # Espaces plus propres
    df_pca['groupe'] = [cluster_names[c] for c in clusters]

    # Groupement pour l'affichage
    grouped = df_pca.groupby(['Dim1', 'Dim2', 'groupe'])['nom'].apply(list).reset_index()
    grouped['text'] = grouped['nom'].apply(lambda lst: '<br>'.join(lst))
    grouped.sort_values('groupe')

    colors = ['#E74C3C', '#27AE60', '#2980B9']

    fig = px.scatter(
        grouped,
        x='Dim1',
        y='Dim2',
        hover_name='text',
        color='groupe',
        category_orders={'groupe': ['0', '1', '2']},
        color_discrete_sequence=colors
    )

    fig.update_traces(marker=dict(size=6, opacity=1), textposition='top center') # formattage du plot
    fig.update_layout(
        height=900,
        width=1200,
        showlegend=True,
        title=dict(
            text="Adaptation des différents arbres",
            x=0.5,
            xanchor='center',
            yanchor='top',
            font=dict(
                family="sans-serif",
                size=30
            ),
            pad=dict(t=10)
        ),

        legend=dict(
            yanchor="top",
            y=0.99,
            xanchor="left",
            x=1.02,
            bgcolor='rgba(255, 255, 255, 0.8)',
            bordercolor='black',
            borderwidth=1
        ),
        margin=dict(l=40, r=200, t=60, b=40),
        coloraxis_showscale=False,
        plot_bgcolor='rgba(200, 200, 200, 1)',
        font=dict(
                size=20
            ),
    )

    return fig

def statisticator_style(fig): # ajouter les composants et le style
    html_content = f"""
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Statistiques des arbres</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style/style.css">
        <link rel="stylesheet" href="style/styleHeaderFooter.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    </head>
    <body>
        <?php include 'components/header.php'; ?>
        <main class="container">
            {fig.to_html(include_plotlyjs=True, full_html=False)}
        </main>
        <?php include 'components/footer.php'; ?>
    </body>
    </html>
    """

    with open("web/statisticator.php", "w", encoding="utf-8") as f:
        f.write(html_content)

    return
