<template>
  <div class="bg-gray-100">
    <AppHeader />

    <div class="bg-primary pb-32">
      <div class="container mx-auto px-4">
        <div class="pt-10">
          <h1 class="text-3xl font-bold text-white">
            Mentions légales
          </h1>
        </div>
      </div>
    </div>

    <div class="-mt-32">
      <div class="container mx-auto px-4 my-12">
        <div
          class="bg-white rounded-lg shadow px-4 py-8 sm:p-8 lg:p-12 xl:p-16"
        >
          <p class="text-lg lg:text-xl font-medium text-gray-900">
            La présente plateforme fait partie intégrante du système
            d’information de la Réserve civique.
          </p>
          <p class="text-lg lg:text-xl font-medium text-gray-900">
            La plateforme vise à permettre aux volontaires et aux structures de
            se mobiliser face à l’urgence de la crise sanitaire Covid-19 en
            permettant aux personnes morales de déposer des offres de mission et
            aux volontaires d’y répondre.
          </p>
        </div>

        <div
          class="mt-8 bg-white rounded-lg shadow px-4 py-8 sm:p-8 lg:p-12 xl:p-16"
        >
          <div class="flex flex-wrap -m-4">
            <div class="p-4 w-full lg:w-1/2">
              <div class="text-lg lg:text-xl font-medium text-gray-900">
                Directeur de la publication
              </div>
            </div>
            <div class="p-4 w-full lg:w-1/2 text-gray-500">
              <p>
                Le directeur de la publication est Patrice LATRON, Préfet,
                Conseiller du Gouvernement.
              </p>
            </div>
          </div>
        </div>

        <div
          class="mt-8 bg-white rounded-lg shadow px-4 py-8 sm:p-8 lg:p-12 xl:p-16"
        >
          <div class="flex flex-wrap -m-4">
            <div class="p-4 w-full lg:w-1/2">
              <div class="text-lg lg:text-xl font-medium text-gray-900">
                Hébergement de la plateforme
              </div>
            </div>
            <div class="p-4 w-full lg:w-1/2 text-gray-500">
              <p>
                La plateforme est hébergée par :
              </p>
              <p>
                <b>Scalingo SAS</b><br />
                15 avenue du Rhin<br />
                67100 Strasbourg France
              </p>
            </div>
          </div>
        </div>

        <div
          class="mt-8 bg-white rounded-lg shadow px-4 py-8 sm:p-8 lg:p-12 xl:p-16"
        >
          <div class="flex flex-wrap -m-4">
            <div class="p-4 w-full lg:w-1/2">
              <div class="text-lg lg:text-xl font-medium text-gray-900">
                Protection des données à caractère personnel
              </div>
            </div>
            <div class="p-4 w-full lg:w-1/2 text-gray-500">
              <p>
                La présente plateforme est un service numérique destiné à
                organiser la mobilisation citoyenne face à la crise COVID-19.
                Elle permet à toute personne, âgée de 16 ans révolus, de
                s’engager pour des missions de volontariat.
              </p>
              <p>
                Ces missions sont proposées par des acteurs publics ou
                associatifs, elles sont liées directement ou indirectement, aux
                urgences découlant de la crise sanitaire du virus Covid-19.
              </p>
              <p>
                Le ministère de l’Education nationale et de la Jeunesse est le
                responsable du traitement des données à caractère personnel
                traitées dans le cadre de cette plateforme. Le ministère est
                particulièrement attentif à la protection des données à
                caractère personnel et s’engage à ce que le traitement de vos
                données, soient conformes au règlement n°2016/679 du Parlement
                et du Conseil relatif à la protection des personnes physiques à
                l’égard du traitement des données à caractère personnel et à la
                libre circulation de ces données (RGPD), à la directive
                2002/58/CE du 12 juillet 2002 sur la protection de la vie privée
                dans le secteur des communications électroniques et à la loi
                n°78-17 du 6 janvier 1978 modifiée relative à l’informatique,
                aux fichiers et aux libertés.
              </p>
              <p>
                Le ministère de l’Education nationale et de la Jeunesse est
                autorisée à traiter des données en vertu de l’article 6-e du
                RGPD, le traitement étant nécessaire à l’exécution d’une mission
                d’intérêt public ou relevant de l’exercice de l’autorité
                publique dont est investi le responsable du traitement.
              </p>
              <p>
                Les données à caractère personnel traitées dans le cadre de
                cette plateforme ne sont ni vendues, ni cédées à des tiers.
                Seuls les agents habilités et les représentants des structures
                accueillant des volontaires ont accès aux informations dans la
                limite de leur droit d’en connaitre.
              </p>
              <p>
                Pour plus d’informations, vous pouvez consulter notre politique
                de confidentialité.
              </p>
            </div>
          </div>
        </div>

        <div
          class="mt-8 bg-white rounded-lg shadow px-4 py-8 sm:p-8 lg:p-12 xl:p-16"
        >
          <div class="flex flex-wrap -m-4">
            <div class="p-4 w-full lg:w-1/2">
              <div class="text-lg lg:text-xl font-medium text-gray-900">
                Exercice de vos droits
              </div>
            </div>
            <div class="p-4 w-full lg:w-1/2 text-gray-500">
              <p>
                Les utilisateurs du site peuvent exercer leurs droits auprès du
                ministère de l’Education nationale et de la Jeunesse.
              </p>
              <p>
                <b>Par voie postale&nbsp;:</b><br />
                Ministère de l’Education nationale et de la Jeunesse<br />
                A l’attention du délégué à la protection des données (DPD)<br />
                110, rue de Grenelle<br />
                75357 Paris Cedex 07<br />
              </p>
              <p>
                <b>Par voie électronique</b>, à l’adresse suivante&nbsp;:
                <a
                  class="text-blue-900 hover:underline font-medium"
                  href="mailto:covid19@mail.reserve-civique.gouv.fr"
                  >covid19@mail.reserve-civique.gouv.fr</a
                >
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <AppFooter />
  </div>
</template>

<script>
export default {
  name: 'FrontLegalNotice',
}
</script>
