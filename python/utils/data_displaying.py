import pandas as pd
import plotly.express as px
from sklearn.cluster import AgglomerativeClustering
from sklearn.decomposition import PCA

from python.utils import (extract_data_for_city, find_closest_trees,
                          get_coordinates)


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
        n_clusters=5,
        linkage='single',
        metric='euclidean'
    )
    clusters = agglo.fit_predict(X_pca)

    # Préparation du DataFrame
    df_pca = pd.DataFrame(X_pca, columns=['Dim1', 'Dim2'])
    df_pca['nom'] = df['genre_francais']
    df_pca['cluster'] = clusters

    # Groupement pour l'affichage
    grouped = df_pca.groupby(['Dim1', 'Dim2', 'cluster'])['nom'].apply(list).reset_index()
    grouped['text'] = grouped['nom'].apply(lambda lst: '<br>'.join(lst))

    colors = ['#E74C3C', '#27AE60', '#2980B9']



    leg = """<b>📌 Préférences écologiques des arbres</b><br>
🔹 <b>En haut à droite</b> : espèce aimant la chaleur et la lumière<br>
🔹 <b>En bas à droite</b> : espèce aimant la chaleur, la lumière et ayant besoin de plus d'eau<br>
🔹 <b>En haut à gauche</b> : espèce peu exigeante, pousse sur sols neutres, résistante au froid<br>
🔹 <b>En bas à gauche</b> : espèce de milieux humides et riches, résistante au froid<br>
🔹 <b>Centre</b> : espèce polyvalente, adaptée à plusieurs conditions"""


    fig = px.scatter(
        grouped,
        x='Dim1',
        y='Dim2',
        hover_name='text',
        color='cluster',
        color_continuous_scale=colors,
        labels={'cluster': 'Groupe'},
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

        margin=dict(l=40, r=200, t=60, b=40),
        coloraxis_showscale=False,
        plot_bgcolor='rgba(200, 200, 200, 1)',
        font=dict(
                size=20
            ),
        annotations=[
            dict(
                x=1,
                y=0.99,
                xref='paper',
                yref='paper',
                text=leg,
                showarrow=False,
                align='left',
                bordercolor='black',
                borderwidth=1,
                borderpad=10,
                bgcolor='white',
                font=dict(size=12),
            )
        ]
    )

    return fig

def statisticator_style(fig): # ajouter les composants et le style
    html_content = f"""
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Statistiques des arbres</title>
        <link rel="stylesheet" href="style/style.css">
        <link rel="stylesheet" href="style/styleHeaderFooter.css">
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
