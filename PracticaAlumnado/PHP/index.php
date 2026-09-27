<?php
require_once __DIR__ . "/conexion.php";

$mensaje = "";
$cursos = ["1º", "2º", "3º", "4º"];
$alumnoEditar = null;
$valoresFormulario = [
	"nombre" => "",
	"apellidos" => "",
	"fecha_nacimiento" => "",
	"curso" => "",
	"email" => ""
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
	$accion = $_POST["accion"] ?? "";
	$id = (int) ($_POST["id"] ?? 0);

	if ($accion === "eliminar" && $id > 0) {
		mysqli_query($conexion, "DELETE FROM alumnos WHERE id = $id");
		$mensaje = "Matrícula eliminada.";
	} elseif ($accion === "crear" || $accion === "actualizar") {
		$nombre = trim($_POST["nombre"] ?? "");
		$apellidos = trim($_POST["apellidos"] ?? "");
		$fechaNacimiento = $_POST["fecha_nacimiento"] ?? "";
		$curso = $_POST["curso"] ?? "";
		$email = trim($_POST["email"] ?? "");
		$contrasena = $_POST["contrasena"] ?? "";

		$valoresFormulario = [
			"nombre" => $nombre,
			"apellidos" => $apellidos,
			"fecha_nacimiento" => $fechaNacimiento,
			"curso" => $curso,
			"email" => $email
		];
		if ($accion === "actualizar" && $id > 0) {
			$alumnoEditar = ["id" => $id];
		}

		if ($nombre === "" || $apellidos === "" || $fechaNacimiento === "" ||
			$curso === "" || $email === "" || $contrasena === "") {
			$mensaje = "Todos los campos son obligatorios.";
		} elseif (!in_array($curso, $cursos, true)) {
			$mensaje = "El curso no es válido.";
		} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$mensaje = "Escribe un email válido.";
		} else {
			$cursoSQL = mysqli_real_escape_string($conexion, $curso);
			$condicionId = "";
			if ($accion === "actualizar") {
				$condicionId = " AND id <> $id";
			}

			$consulta = mysqli_query(
				$conexion,
				"SELECT COUNT(*) AS total FROM alumnos WHERE curso = '$cursoSQL'$condicionId"
			);
			$resultado = mysqli_fetch_assoc($consulta);
			$alumnosCurso = (int) $resultado["total"];

			if ($alumnosCurso >= 25) {
				$mensaje = "No se puede matricular a otra persona: ese curso ya tiene 25 alumnos.";
			} else {
				$nombreSQL = mysqli_real_escape_string($conexion, $nombre);
				$apellidosSQL = mysqli_real_escape_string($conexion, $apellidos);
				$fechaSQL = mysqli_real_escape_string($conexion, $fechaNacimiento);
				$emailSQL = mysqli_real_escape_string($conexion, $email);
				$contrasenaSQL = mysqli_real_escape_string($conexion, $contrasena);

				if ($accion === "crear") {
					$sql = "INSERT INTO alumnos (nombre, apellidos, fecha_nacimiento, curso, email, contrasena)
						VALUES ('$nombreSQL', '$apellidosSQL', '$fechaSQL', '$cursoSQL', '$emailSQL', '$contrasenaSQL')";
					mysqli_query($conexion, $sql);
					$mensaje = "Matrícula guardada.";
				} else {
					$sql = "UPDATE alumnos SET nombre = '$nombreSQL', apellidos = '$apellidosSQL',
						fecha_nacimiento = '$fechaSQL', curso = '$cursoSQL', email = '$emailSQL', contrasena = '$contrasenaSQL'
						WHERE id = $id";
					mysqli_query($conexion, $sql);
					$mensaje = "Matrícula modificada.";
				}

				$alumnoEditar = null;
				$valoresFormulario = [
					"nombre" => "",
					"apellidos" => "",
					"fecha_nacimiento" => "",
					"curso" => "",
					"email" => ""
				];
			}
		}
	}
}

if (isset($_GET["editar"])) {
	$idEditar = (int) $_GET["editar"];
	$consulta = mysqli_query(
		$conexion,
		"SELECT id, nombre, apellidos, fecha_nacimiento, curso, email FROM alumnos WHERE id = $idEditar"
	);
	$alumnoEditar = mysqli_fetch_assoc($consulta);
	if ($alumnoEditar !== null) {
		$valoresFormulario = $alumnoEditar;
	}
}

$alumnos = mysqli_query(
	$conexion,
	"SELECT id, nombre, apellidos, fecha_nacimiento, curso, email FROM alumnos ORDER BY apellidos, curso"
);

$matriculadosPorCurso = [
	"1º" => 0,
	"2º" => 0,
	"3º" => 0,
	"4º" => 0
];
$consultaConteos = mysqli_query(
	$conexion,
	"SELECT curso, COUNT(*) AS total FROM alumnos GROUP BY curso"
);
while ($filaCurso = mysqli_fetch_assoc($consultaConteos)) {
	$matriculadosPorCurso[$filaCurso["curso"]] = (int) $filaCurso["total"];
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Matriculación del alumnado</title>
	<link rel="stylesheet" href="../estilos/estilos.css">
</head>
<body>
	<main>
		<h1>Matriculación de alumnado</h1>
		<?php if ($mensaje !== ""): ?>
			<p class="mensaje"><?php echo htmlspecialchars($mensaje, ENT_QUOTES, "UTF-8"); ?></p>
		<?php endif; ?>

		<p class="conteos">
			Alumnado por curso:
			<?php foreach ($cursos as $curso): ?>
				<strong><?php echo htmlspecialchars($curso, ENT_QUOTES, "UTF-8"); ?>:</strong>
				<?php echo $matriculadosPorCurso[$curso]; ?>/25
			<?php endforeach; ?>
		</p>

		<h2><?php echo $alumnoEditar === null ? "Nueva matrícula" : "Modificar matrícula"; ?></h2>
		<form class="formulario" action="index.php" method="POST">
			<input type="hidden" name="accion" value="<?php echo $alumnoEditar === null ? "crear" : "actualizar"; ?>">
			<?php if ($alumnoEditar !== null): ?>
				<input type="hidden" name="id" value="<?php echo (int) $alumnoEditar["id"]; ?>">
			<?php endif; ?>
			<div class="campo">
				<label for="nombre">Nombre</label>
				<input type="text" id="nombre" name="nombre" maxlength="50" required value="<?php echo htmlspecialchars($valoresFormulario["nombre"], ENT_QUOTES, "UTF-8"); ?>">
			</div>
			<div class="campo">
				<label for="apellidos">Apellidos</label>
				<input type="text" id="apellidos" name="apellidos" maxlength="100" required value="<?php echo htmlspecialchars($valoresFormulario["apellidos"], ENT_QUOTES, "UTF-8"); ?>">
			</div>
			<div class="campo">
				<label for="fecha_nacimiento">Fecha de nacimiento</label>
				<input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required value="<?php echo htmlspecialchars($valoresFormulario["fecha_nacimiento"], ENT_QUOTES, "UTF-8"); ?>">
			</div>
			<div class="campo">
				<label for="curso">Curso</label>
				<select id="curso" name="curso" required>
					<option value="">Selecciona un curso</option>
					<?php foreach ($cursos as $curso): ?>
						<option value="<?php echo htmlspecialchars($curso, ENT_QUOTES, "UTF-8"); ?>" <?php echo $valoresFormulario["curso"] === $curso ? "selected" : ""; ?>><?php echo htmlspecialchars($curso, ENT_QUOTES, "UTF-8"); ?> de ESO</option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="campo">
				<label for="email">Email de Educamos</label>
				<input type="email" id="email" name="email" maxlength="100" required value="<?php echo htmlspecialchars($valoresFormulario["email"], ENT_QUOTES, "UTF-8"); ?>">
			</div>
			<div class="campo">
				<label for="contrasena">Contraseña de Educamos</label>
				<input type="password" id="contrasena" name="contrasena" required>
			</div>
			<div class="botones">
				<button type="submit"><?php echo $alumnoEditar === null ? "Matricular" : "Guardar cambios"; ?></button>
				<?php if ($alumnoEditar !== null): ?>
					<a href="index.php">Cancelar</a>
				<?php endif; ?>
			</div>
		</form>

		<h2>Alumnado matriculado</h2>
		<div class="tabla-contenedor">
			<table>
				<thead>
					<tr>
						<th>Nombre</th>
						<th>Apellidos</th>
						<th>Fecha de nacimiento</th>
						<th>Curso</th>
						<th>Email</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
					<?php if (mysqli_num_rows($alumnos) === 0): ?>
						<tr><td colspan="6">Todavía no hay alumnado matriculado.</td></tr>
					<?php else: ?>
						<?php while ($alumno = mysqli_fetch_assoc($alumnos)): ?>
							<tr>
								<td><?php echo htmlspecialchars($alumno["nombre"], ENT_QUOTES, "UTF-8"); ?></td>
								<td><?php echo htmlspecialchars($alumno["apellidos"], ENT_QUOTES, "UTF-8"); ?></td>
								<td><?php echo htmlspecialchars($alumno["fecha_nacimiento"], ENT_QUOTES, "UTF-8"); ?></td>
								<td><?php echo htmlspecialchars($alumno["curso"], ENT_QUOTES, "UTF-8"); ?></td>
								<td><?php echo htmlspecialchars($alumno["email"], ENT_QUOTES, "UTF-8"); ?></td>
								<td class="acciones">
									<a href="index.php?editar=<?php echo (int) $alumno["id"]; ?>">Modificar</a>
									<form action="index.php" method="POST">
										<input type="hidden" name="accion" value="eliminar">
										<input type="hidden" name="id" value="<?php echo (int) $alumno["id"]; ?>">
										<button type="submit">Eliminar</button>
									</form>
								</td>
							</tr>
						<?php endwhile; ?>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</main>
</body>
</html>
<?php mysqli_close($conexion); ?>